<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" shape-rendering="crispEdges">
<rect width="20" height="20" rx="4" fill="#86e1c6"/>
<g transform="translate(-23 1)">@foreach (App\Support\Spaniel::runs(25, 0, 40, 17) as $r)<rect x="{{ $r['x'] }}" y="{{ $r['y'] }}" width="{{ $r['w'] }}" height="1" fill="{{ $r['fill'] }}"/>@endforeach</g>
</svg>
