@php
    $rows = [
        '...........................HKHKHK........',
        '...........................KHHBBHK.......',
        '..........................KBBBHHBBK......',
        '.........................KBBBBBBBBBK.....',
        '.........................KBBBBBBTTBK.....',
        '.....K...................KBBHMBBEWTBKKKK.',
        '....KHK.................KBKHMBHTTTTLLLWNK',
        '...KHBMK.................KHMBHMTTTTTLTNNK',
        '....KHBMK....KKKKKKKKKKKKKMBHMBTTSTTTTKK.',
        '.....KMBMK.KKBBBBMBBBBMBBKBHMBHKSSSSSK...',
        '......KMBKKBBBBMBBBBMBBBBKHMBHMKBKKKK....',
        '.......KKKMBBBBMBBBBMBBBBKMBHMBKBK.......',
        '........KBBBBMBBBBMBBBBMBKBHMBHKBMK......',
        '........KBBBBMBBBBMBBBBMBKHMBHMBKMK......',
        '........KBBMBBBBMBBBBMBBBKMBHMBHKBK......',
        '........KBBMBBBBMBBBBMBBBBKHMBHSKBK......',
        '.........KBBBBMBBBBMBBBBMKMSMSMKBK.......',
        '.........KBBBBMBMMMMMMBBMBKKBBBBK........',
        '.........KBBBBBBKMKMKMKKBBBMBBBK.........',
        '........KMTTSKBBKK.K.K..KBBMTTSK.........',
        '........KMTTSKSSK.......KSSMTTSK.........',
        '........KMTTSKSSK.......KSSMTTSK.........',
        '.........KTTSKSSK.......KSSKTTSK.........',
        '.........KTTSKSSK.......KSSKTTSK.........',
        '.........KTTSKSSK.......KSSKTTSK.........',
        '.........KLLLLSSSK......KSSSLLLLK........',
        '..........KKKKKKK........KKKKKKK.........',
        '.........................................',
    ];
    $fill = [
        'K' => '#111011',
        'B' => '#2a2220',
        'M' => '#43352f',
        'H' => '#67544a',
        'T' => '#b46a32',
        'L' => '#d99350',
        'S' => '#87481f',
        'N' => '#0b0a0a',
        'E' => '#0b0a0a',
        'W' => '#f3ece6',
    ];
    $isTail = fn ($x, $y) => $x <= 8 && $y <= 10;
    $body = $tail = [];
    foreach ($rows as $y => $row) {
        for ($x = 0; $x < strlen($row); $x++) {
            $c = $row[$x];
            if (! isset($fill[$c])) continue;
            $w = 1;
            while ($c !== 'E' && ($row[$x + $w] ?? null) === $c && $isTail($x + $w, $y) === $isTail($x, $y)) $w++;
            $isTail($x, $y) ? $tail[] = [$x, $y, $w, $c] : $body[] = [$x, $y, $w, $c];
            $x += $w - 1;
        }
    }
@endphp
<svg class="spaniel" viewBox="0 0 41 28" width="51" height="35" shape-rendering="crispEdges" aria-hidden="true" focusable="false">
    <g class="spaniel__tail">@foreach ($tail as [$x, $y, $w, $c])<rect x="{{ $x }}" y="{{ $y }}" width="{{ $w }}" height="1" fill="{{ $fill[$c] }}"/>@endforeach</g>
    @foreach ($body as [$x, $y, $w, $c])<rect x="{{ $x }}" y="{{ $y }}" width="{{ $w }}" height="1" fill="{{ $fill[$c] }}" @if ($c === 'E') class="spaniel__eye" @endif/>@endforeach
</svg>
