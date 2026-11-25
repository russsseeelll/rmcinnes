
const headers = () => ({
    Accept: 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
});

function withQuery(url, params) {
    const u = new URL(url, location.origin);
    for (const [key, value] of Object.entries(params)) if (value) u.searchParams.set(key, value);
    return u.toString();
}

function clearErrors(form) {
    form.querySelectorAll('.field__err').forEach((el) => el.remove());
    form.querySelectorAll('[aria-invalid]').forEach((el) => {
        el.removeAttribute('aria-invalid');
        el.removeAttribute('aria-describedby');
    });
    const banner = form.querySelector('[data-form-error]');
    banner.hidden = true;
    banner.textContent = "Some fields need fixing. They're marked below.";
}

function showErrors(form, errors) {
    let first = null;
    for (const [name, messages] of Object.entries(errors)) {
        const input = form.elements[name];
        if (!input) continue;
        const err = document.createElement('p');
        err.className = 'field__err';
        err.id = `${input.id}-err`;
        err.textContent = messages[0];
        input.after(err);
        input.setAttribute('aria-invalid', 'true');
        input.setAttribute('aria-describedby', err.id);
        first ??= input;
    }
    const banner = form.querySelector('[data-form-error]');
    banner.hidden = false;
    (first ?? banner).focus();
}

function showBanner(form, text) {
    const banner = form.querySelector('[data-form-error]');
    banner.textContent = text;
    banner.hidden = false;
    banner.focus();
}

function apiForm(form) {
    const submit = form.querySelector('[data-submit]');
    const label = submit.textContent;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors(form);
        const body = new FormData(form);
        if (form.dataset.method !== 'POST') body.append('_method', form.dataset.method);
        form.querySelectorAll('input[type=file]').forEach((f) => f.files.length || body.delete(f.name));
        form.querySelectorAll('input[type=checkbox]:not(:checked)').forEach((c) => body.set(c.name, '0'));

        submit.disabled = true;
        submit.textContent = 'Saving…';
        try {
            const res = await fetch(form.action, { method: 'POST', headers: headers(), body, credentials: 'same-origin' });
            const data = await res.json().catch(() => ({}));
            if (res.ok) {
                location.assign(withQuery(data.data.url, { done: form.dataset.success }));
                return;
            }
            if (res.status === 422) showErrors(form, data.errors ?? {});
            else if (res.status === 401 || res.status === 419) showBanner(form, 'Your login has expired. Copy anything you need, then log in again in another tab and save.');
            else showBanner(form, data.message || "Couldn't save just now. Try again.");
        } catch {
            showBanner(form, "Couldn't save just now. Check your connection and try again.");
        }
        submit.disabled = false;
        submit.textContent = label;
    });

    form.querySelectorAll('[data-count-for]').forEach((out) => {
        const field = document.getElementById(out.dataset.countFor);
        const update = () => (out.textContent = field.value.length);
        field.addEventListener('input', update);
        update();
    });
}

async function apiDelete(button) {
    if (!confirm(`Delete “${button.dataset.title}”? This can't be undone.`)) return;
    button.disabled = true;
    const res = await fetch(button.dataset.apiDelete, { method: 'DELETE', headers: headers(), credentials: 'same-origin' }).catch(() => null);
    if (res?.ok) {
        location.assign(withQuery(button.dataset.redirect ?? '/portfolio', { deleted: button.dataset.title, in: button.dataset.scope }));
    } else {
        button.disabled = false;
        alert("Couldn't delete that project just now. Try again.");
    }
}

document.querySelectorAll('[data-api-form]').forEach(apiForm);
document.querySelectorAll('[data-api-delete]').forEach((b) => b.addEventListener('click', () => apiDelete(b)));
