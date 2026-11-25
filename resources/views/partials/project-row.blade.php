@php($tags = array_filter(array_map('trim', explode(',', (string) $p->tags))))
<li class="ledger__row">
    <a class="ledger__link" href="{{ route('portfolio.single', $p->slug) }}">
        <span class="ledger__meta">
            <span class="ledger__cat">{{ $p->category }}</span>
            <time datetime="{{ $p->date }}">{{ \Illuminate\Support\Carbon::parse($p->date)->format('M Y') }}</time>
        </span>
        <span class="ledger__title">{{ $p->title }}</span>
        <span class="ledger__summary">{{ $p->summary }}</span>
        @if ($tags)
            <span class="ledger__tags">
                @foreach ($tags as $tag)<span>{{ $tag }}</span>@endforeach
            </span>
        @endif
        @if ($p->image)
            <img class="ledger__img" src="{{ asset($p->image) }}" alt="" loading="lazy">
        @endif
    </a>
    @auth
        <div class="row-admin ledger__admin">
            <a class="btn btn--ink btn--sm" href="{{ route('portfolio.edit', $p->slug) }}">Edit</a>
            <button type="button" class="btn btn--danger btn--sm" data-api-delete="{{ url('/api/projects/'.$p->slug) }}"
                data-title="{{ $p->title }}" data-redirect="{{ route('portfolio') }}">Delete</button>
        </div>
    @endauth
</li>
