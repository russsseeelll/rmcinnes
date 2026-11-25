@php($runs = collect(App\Support\Spaniel::runs()))
<svg class="spaniel" viewBox="0 0 41 28" width="51" height="35" shape-rendering="crispEdges" aria-hidden="true" focusable="false">
    <g class="spaniel__tail">@foreach ($runs->where('tail', true) as $r)<rect x="{{ $r['x'] }}" y="{{ $r['y'] }}" width="{{ $r['w'] }}" height="1" fill="{{ $r['fill'] }}"/>@endforeach</g>
    @foreach ($runs->where('tail', false) as $r)<rect x="{{ $r['x'] }}" y="{{ $r['y'] }}" width="{{ $r['w'] }}" height="1" fill="{{ $r['fill'] }}" @if ($r['eye']) class="spaniel__eye" @endif/>@endforeach
</svg>
