// Cluster: an original falling-workload packing game. Rectangular workloads drop into a
// three-node cluster; a full row ships a rollout. Autopilot plays until a visitor takes over.

const INK = '#111011';
const COLORS = ['#86e1c6', '#ffb547', '#ff7663', '#8ea8ff', '#c4a9ff'].map((fill) => ({ fill, ink: INK }));
// Fallback labels; the page normally supplies the owner's skills via data-workloads.
const DEFAULT_WORKLOADS = [
    'nginx', 'postgres', 'redis', 'ansible', 'grafana', 'prometheus', 'jenkins', 'rundeck',
    'ldap', 'bind9', 'nfs', 'docker', 'sssd', 'snipe-it', 'k8s-api', 'shibboleth', 'haproxy', 'kvm',
];
// [w, h] in cells; rectangles only, rotation swaps the sides.
const SHAPES = [[1, 1], [2, 1], [3, 1], [2, 1], [3, 1], [2, 2], [3, 2], [1, 2], [2, 3]];

const pick = (arr) => arr[Math.floor(Math.random() * arr.length)];

// Leaderboard (routes/web.php). Failures are silent: the game works without it.
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const api = (url, body) => fetch(url, {
    method: body ? 'POST' : 'GET',
    headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() },
    body: body ? JSON.stringify(body) : undefined,
}).then(async (r) => {
    const data = await r.json().catch(() => ({}));
    if (!r.ok) throw new Error(data.message || 'Request failed');
    return data;
});

export function cluster(root) {
    const canvas = root.querySelector('[data-cluster-canvas]');
    const ctx = canvas.getContext('2d');
    const nodesEl = root.querySelector('[data-cluster-nodes]');
    const scoreEl = root.querySelector('[data-cluster-score]');
    const toggle = root.querySelector('[data-cluster-toggle]');
    const toggleLabel = root.querySelector('[data-cluster-toggle-label]');
    const overlay = root.querySelector('[data-cluster-overlay]');
    const finalEl = root.querySelector('[data-cluster-final]');
    const pad = root.querySelector('[data-cluster-pad]');
    const board = root.querySelector('[data-board]');
    const boardTitle = root.querySelector('[data-board-title]');
    const bestEl = root.querySelector('[data-cluster-best]');
    const restart = root.querySelector('[data-cluster-restart]');
    let workloads = DEFAULT_WORKLOADS;
    try {
        const fromPage = JSON.parse(root.dataset.workloads || '[]');
        if (fromPage.length >= 4) workloads = fromPage;
    } catch { /* keep defaults */ }
    const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

    let cols = 0, rows = 0, cell = 0, ox = 0, oy = 0, dpr = 1, cssW = 0, cssH = 0;
    let grid, owners = new Map(), nextId = 1;
    let active = null;
    let mode = 'auto';           // 'auto' | 'play' | 'over'
    let score = 0;
    let clearing = null;         // { rows: [...], t0 }
    let visible = true, running = false;
    let lastFall = 0, lastStep = 0, target = null;

    const fallMs = () => mode === 'play' ? Math.max(120, 650 * Math.pow(0.94, score)) : 105;

    // ---------- board ----------

    function layout() {
        const r = canvas.getBoundingClientRect();
        cssW = r.width; cssH = r.height;
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = Math.round(cssW * dpr);
        canvas.height = Math.round(cssH * dpr);
        const nextCols = cssW >= 900 ? 18 : cssW >= 600 ? 15 : 12;
        cell = Math.floor(cssW / nextCols);
        const nextRows = Math.max(8, Math.floor(cssH / cell));
        ox = Math.floor((cssW - nextCols * cell) / 2);
        oy = Math.floor(cssH - nextRows * cell);
        if (nextCols !== cols || nextRows !== rows) {
            cols = nextCols; rows = nextRows;
            reset();
        }
        draw(performance.now());
    }

    function reset() {
        grid = new Int32Array(cols * rows);
        owners = new Map();
        active = null; target = null; clearing = null;
        setScore(0);
        updateNodes();
        if (mode === 'auto') prefill(); // start mid-shift, not on an empty cluster
    }

    const at = (x, y) => grid[y * cols + x];
    const fits = (x, y, w, h, g = grid) => {
        if (x < 0 || x + w > cols || y + h > rows) return false;
        for (let j = Math.max(0, y); j < y + h; j++)
            for (let i = x; i < x + w; i++) if (g[j * cols + i]) return false;
        return true;
    };
    const dropY = (x, w, h, g = grid, from = 0) => {
        let y = from;
        if (!fits(x, y, w, h, g)) return null;
        while (fits(x, y + 1, w, h, g)) y++;
        return y;
    };

    function spawn() {
        let [w, h] = pick(SHAPES);
        if (Math.random() < 0.5 && w !== h) [w, h] = [h, w];
        const x = Math.floor(Math.random() * (cols - w + 1));
        const id = nextId++;
        const piece = { id, x, y: 0, ry: -h, w, h, ...pick(COLORS), label: pick(workloads) };
        if (!fits(x, 0, w, h)) return gameOver();
        active = piece;
        target = mode === 'auto' ? plan(piece) : null;
    }

    function lock() {
        const p = active;
        for (let j = p.y; j < p.y + p.h; j++)
            for (let i = p.x; i < p.x + p.w; i++) grid[j * cols + i] = p.id;
        owners.set(p.id, { fill: p.fill, ink: p.ink, label: p.label });
        active = null;
        const full = [];
        for (let j = 0; j < rows; j++) {
            let ok = true;
            for (let i = 0; i < cols; i++) if (!at(i, j)) { ok = false; break; }
            if (ok) full.push(j);
        }
        if (full.length) clearing = { rows: full, t0: performance.now() };
        updateNodes();
    }

    function finishClear() {
        const keep = [];
        for (let j = 0; j < rows; j++) if (!clearing.rows.includes(j)) keep.push(grid.slice(j * cols, j * cols + cols));
        grid = new Int32Array(cols * rows);
        const offset = rows - keep.length;
        keep.forEach((row, k) => grid.set(row, (k + offset) * cols));
        setScore(score + clearing.rows.length);
        clearing = null;
        updateNodes();
    }

    function gameOver() {
        if (mode === 'play') {
            mode = 'over';
            finalEl.textContent = score;
            overlay.hidden = false;
            restart.focus();
            api('/scores').then((top) => showBoard(top)).catch(() => {});
            return;
        }
        // Autopilot never loses on screen: wipe the cluster and carry on.
        reset();
    }

    function setScore(n) {
        score = n;
        scoreEl.textContent = String(n).padStart(3, '0');
    }

    function updateNodes() {
        const per = cols / 3;
        const labels = [0, 1, 2].map((n) => {
            let used = 0;
            for (let j = 0; j < rows; j++)
                for (let i = n * per; i < (n + 1) * per; i++) if (grid[j * cols + i]) used++;
            return `node-0${n + 1} · ${Math.round((used / (per * rows)) * 100)}%`;
        });
        nodesEl.innerHTML = labels.map((l) => `<span>${l}</span>`).join('');
    }

    // ---------- autopilot ----------

    function evaluate(g) {
        let holes = 0, agg = 0, bump = 0, lines = 0, prev = -1;
        for (let j = 0; j < rows; j++) {
            let ok = true;
            for (let i = 0; i < cols; i++) if (!g[j * cols + i]) { ok = false; break; }
            if (ok) lines++;
        }
        for (let i = 0; i < cols; i++) {
            let top = rows;
            for (let j = 0; j < rows; j++) if (g[j * cols + i]) { top = j; break; }
            const height = rows - top;
            for (let j = top + 1; j < rows; j++) if (!g[j * cols + i]) holes++;
            agg += height;
            if (prev >= 0) bump += Math.abs(height - prev);
            prev = height;
        }
        return lines * 9 - holes * 7 - agg * 0.3 - bump * 0.35;
    }

    function plan(p) {
        let best = null;
        const orients = p.w === p.h ? [[p.w, p.h]] : [[p.w, p.h], [p.h, p.w]];
        for (const [w, h] of orients) {
            for (let x = 0; x + w <= cols; x++) {
                const y = dropY(x, w, h);
                if (y === null) continue;
                const g = grid.slice();
                for (let j = y; j < y + h; j++) for (let i = x; i < x + w; i++) g[j * cols + i] = 1;
                const s = evaluate(g) + y * 0.5 + Math.random() * 0.8;
                if (!best || s > best.s) best = { s, x, w, h };
            }
        }
        return best;
    }

    function prefill() {
        // Reduced motion: show a packed cluster at rest instead of animating one.
        for (let n = 0; n < rows * 2; n++) {
            spawn();
            if (!active || !target) break;
            active.w = target.w; active.h = target.h; active.x = target.x;
            active.y = dropY(target.x, target.w, target.h) ?? 0;
            lock();
            if (clearing) finishClear();
            const filled = grid.filter(Boolean).length;
            if (filled > cols * rows * 0.45) break;
        }
        active = null;
        setScore(0);
        updateNodes();
    }

    function autoStep() {
        const p = active;
        if (!p || !target) return;
        if (p.w !== target.w && fits(p.x, p.y, p.h, p.w)) { [p.w, p.h] = [p.h, p.w]; return; }
        if (p.x < target.x && fits(p.x + 1, p.y, p.w, p.h)) p.x++;
        else if (p.x > target.x && fits(p.x - 1, p.y, p.w, p.h)) p.x--;
    }

    // ---------- player ----------

    function command(cmd) {
        if (mode !== 'play' || !active || clearing) return;
        const p = active;
        if (cmd === 'left' && fits(p.x - 1, p.y, p.w, p.h)) p.x--;
        else if (cmd === 'right' && fits(p.x + 1, p.y, p.w, p.h)) p.x++;
        else if (cmd === 'rotate' && p.w !== p.h) {
            // Rotate in place, nudging away from walls when it would clip.
            for (const dx of [0, -1, 1, -2, 2]) {
                if (fits(p.x + dx, p.y, p.h, p.w)) { p.x += dx; [p.w, p.h] = [p.h, p.w]; break; }
            }
        } else if (cmd === 'down') {
            if (fits(p.x, p.y + 1, p.w, p.h)) { p.y++; lastFall = performance.now(); } else lock();
        } else if (cmd === 'drop') {
            p.y = dropY(p.x, p.w, p.h, grid, p.y);
            lock();
        }
        draw(performance.now());
    }

    function startPlay() {
        mode = 'play';
        overlay.hidden = true;
        root.classList.add('is-playing');
        toggle.setAttribute('aria-pressed', 'true');
        toggleLabel.textContent = 'Back to autopilot';
        pad.hidden = false;
        grid = new Int32Array(cols * rows);
        owners = new Map(); active = null; clearing = null;
        setScore(0); updateNodes();
        canvas.focus({ preventScroll: true });
        const r = root.getBoundingClientRect();
        if (r.top < 0 || r.bottom > innerHeight) root.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
        ensureRunning();
    }

    function stopPlay() {
        mode = 'auto';
        overlay.hidden = true;
        root.classList.remove('is-playing');
        toggle.setAttribute('aria-pressed', 'false');
        toggleLabel.textContent = 'Take the controls';
        pad.hidden = true;
        reset();
        ensureRunning();
        draw(performance.now());
    }

    toggle.addEventListener('click', () => (mode === 'auto' ? startPlay() : stopPlay()));
    restart.addEventListener('click', startPlay);

    // ---------- leaderboard ----------

    function showBoard(top) {
        board.replaceChildren(...top.map((row) => {
            const li = document.createElement('li');
            const who = document.createElement('span');
            who.className = 'board__who';
            who.textContent = row.organisation ? `${row.name} @ ${row.organisation}` : row.name;
            const pts = document.createElement('span');
            pts.className = 'board__score';
            pts.textContent = String(row.score).padStart(3, '0');
            li.append(who, pts);
            return li;
        }));
        boardTitle.hidden = top.length === 0;
        showBest(top[0]);
    }

    function showBest(row) {
        bestEl.hidden = !row;
        if (!row) return;
        const pts = document.createElement('b');
        pts.textContent = String(row.score).padStart(3, '0');
        bestEl.replaceChildren('best ', pts, ` by ${row.name}`);
    }

    api('/scores').then((top) => showBest(top[0])).catch(() => {});
    pad.addEventListener('click', (e) => {
        const b = e.target.closest('button[data-cmd]');
        if (b) command(b.dataset.cmd);
    });
    canvas.addEventListener('click', () => mode === 'play' && command('rotate'));

    const KEYS = { ArrowLeft: 'left', ArrowRight: 'right', ArrowUp: 'rotate', x: 'rotate', X: 'rotate', ArrowDown: 'down', ' ': 'drop' };
    window.addEventListener('keydown', (e) => {
        if (mode !== 'play' || !visible) return;
        if (e.target.closest?.('input, textarea, select, [contenteditable]')) return;
        if (e.key === 'Escape') return stopPlay();
        const cmd = KEYS[e.key];
        if (!cmd) return;
        e.preventDefault();
        command(cmd);
    });

    // ---------- loop ----------

    function frame(now) {
        if (!visible || document.hidden || (reduced && mode === 'auto')) { running = false; return; }
        if (mode !== 'over') {
            if (clearing) {
                if (now - clearing.t0 > 420) finishClear();
            } else if (!active) {
                spawn();
                lastFall = now;
            } else {
                if (mode === 'auto' && now - lastStep > 60) { autoStep(); lastStep = now; }
                if (now - lastFall > fallMs()) {
                    if (fits(active.x, active.y + 1, active.w, active.h)) active.y++;
                    else lock();
                    lastFall = now;
                }
            }
        }
        draw(now);
        requestAnimationFrame(frame);
    }

    function ensureRunning() {
        if (running || !visible || document.hidden) return;
        if (reduced && mode === 'auto') return;
        running = true;
        requestAnimationFrame(frame);
    }

    // ---------- drawing ----------

    function draw(now) {
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.clearRect(0, 0, cssW, cssH);

        // Rack holes and node rails.
        ctx.fillStyle = 'rgba(243,236,230,0.07)';
        for (let j = 0; j <= rows; j++)
            for (let i = 0; i <= cols; i++) ctx.fillRect(ox + i * cell - 1, oy + j * cell - 1, 2, 2);
        const per = cols / 3;
        for (let n = 1; n < 3; n++) {
            const x = ox + n * per * cell;
            ctx.fillStyle = INK;
            ctx.fillRect(x - 1.5, 0, 3, cssH);
        }

        // Settled workloads, drawn cell by cell so cleared rows can cut pieces.
        const boxes = new Map();
        for (let j = 0; j < rows; j++) {
            for (let i = 0; i < cols; i++) {
                const id = at(i, j);
                if (!id) continue;
                const o = owners.get(id);
                drawCell(i, j, oy + j * cell, o.fill, id);
                const b = boxes.get(id);
                if (b) { b.x0 = Math.min(b.x0, i); b.x1 = Math.max(b.x1, i); b.y0 = Math.min(b.y0, j); b.y1 = Math.max(b.y1, j); }
                else boxes.set(id, { x0: i, x1: i, y0: j, y1: j, o });
            }
        }
        for (const b of boxes.values()) label(b.o, ox + b.x0 * cell, oy + b.y0 * cell, (b.x1 - b.x0 + 1) * cell, (b.y1 - b.y0 + 1) * cell);

        // Active workload: eased fall, ghost landing spot for players.
        if (active) {
            const p = active;
            p.ry += (p.y - p.ry) * (reduced ? 1 : 0.32);
            if (mode === 'play') {
                const gy = dropY(p.x, p.w, p.h, grid, p.y);
                if (gy !== null && gy !== p.y) {
                    ctx.strokeStyle = 'rgba(134,225,198,0.65)';
                    ctx.setLineDash([4, 4]);
                    ctx.strokeRect(ox + p.x * cell + 2.5, oy + gy * cell + 2.5, p.w * cell - 5, p.h * cell - 5);
                    ctx.setLineDash([]);
                }
            }
            block(ox + p.x * cell, oy + p.ry * cell, p.w * cell, p.h * cell, p.fill);
            label(p, ox + p.x * cell, oy + p.ry * cell, p.w * cell, p.h * cell);
        }

        // Rollout sweep.
        if (clearing) {
            const t = Math.min(1, (now - clearing.t0) / 420);
            for (const j of clearing.rows) {
                const y = oy + j * cell;
                ctx.fillStyle = `rgba(255,181,71,${0.95 * (1 - t * 0.35)})`;
                ctx.fillRect(ox, y, cols * cell * Math.min(1, t * 1.8), cell);
                if (cell >= 22) {
                    ctx.fillStyle = INK;
                    ctx.font = `700 ${Math.min(13, cell * 0.4)}px "Martian Mono", monospace`;
                    ctx.textBaseline = 'middle';
                    ctx.fillText('ROLLOUT SHIPPED', ox + 10, y + cell / 2);
                }
            }
        }
    }

    function drawCell(i, j, y, fill, id) {
        // Outline only where the neighbour belongs to another workload, so pieces read whole.
        const e = 2.5;
        const x = ox + i * cell;
        const l = i === 0 || at(i - 1, j) !== id;
        const r = i === cols - 1 || at(i + 1, j) !== id;
        const t = j === 0 || at(i, j - 1) !== id;
        const b = j === rows - 1 || at(i, j + 1) !== id;
        ctx.fillStyle = fill;
        ctx.fillRect(x, y, cell, cell);
        ctx.fillStyle = INK;
        if (l) ctx.fillRect(x, y, e, cell);
        if (r) ctx.fillRect(x + cell - e, y, e, cell);
        if (t) ctx.fillRect(x, y, cell, e);
        if (b) ctx.fillRect(x, y + cell - e - 3, cell, e + 3);
    }

    function block(x, y, w, h, fill) {
        const e = 2.5;
        ctx.fillStyle = INK;
        ctx.fillRect(x, y, w, h);
        ctx.fillStyle = fill;
        ctx.fillRect(x + e, y + e, w - 2 * e, h - 2 * e - 3);
    }

    function label(o, x, y, w, h) {
        // Status LED, then the workload name if it fits.
        ctx.fillStyle = INK;
        ctx.fillRect(x + 7, y + 7, 4, 4);
        const size = Math.max(9, Math.min(13, cell * 0.36));
        ctx.font = `600 ${size}px "Martian Mono", monospace`;
        ctx.fillStyle = o.ink;
        ctx.textBaseline = 'middle';
        const tw = ctx.measureText(o.label).width;
        if (tw + 14 <= w) {
            ctx.textAlign = 'center';
            ctx.fillText(o.label, x + w / 2, y + h / 2 + 1);
            ctx.textAlign = 'start';
        } else if (tw + 14 <= h) {
            ctx.save();
            ctx.translate(x + w / 2 + 1, y + h / 2);
            ctx.rotate(-Math.PI / 2);
            ctx.textAlign = 'center';
            ctx.fillText(o.label, 0, 0);
            ctx.restore();
        }
    }

    // ---------- lifecycle ----------

    new IntersectionObserver(([e]) => { visible = e.isIntersecting; ensureRunning(); }, { threshold: 0.15 }).observe(root);
    document.addEventListener('visibilitychange', ensureRunning);
    let resizeTimer;
    new ResizeObserver(() => { clearTimeout(resizeTimer); resizeTimer = setTimeout(layout, 120); }).observe(canvas);
    document.fonts?.ready.then(() => draw(performance.now()));
    layout();
    ensureRunning();
}

document.querySelectorAll('[data-cluster]').forEach(cluster);
