@extends('layouts.app', ['title' => $portfolio->title.' · '.$profile->name])

@php($tags = array_filter(array_map('trim', explode(',', (string) $portfolio->tags))))

@section('content')
<article class="project">
    @if ($message = session('message'))
        <p class="form__ok" role="status">{{ $message }}</p>
    @endif

    @auth
        <div class="admin-bar">
            <span class="admin-bar__label">You're logged in</span>
            <a class="btn btn--ink" href="{{ route('portfolio.edit', $portfolio->slug) }}">Edit project</a>
            <form method="POST" action="{{ route('portfolio.destroy', $portfolio->slug) }}"
                onsubmit="return confirm('Delete “{{ addslashes($portfolio->title) }}”? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn--danger">Delete</button>
            </form>
        </div>
    @endauth

    <a class="arrowlink arrowlink--back" href="{{ route('portfolio') }}"><svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg> All projects</a>

    <header class="project__head">
        <h1 class="project__title">{{ $portfolio->title }}</h1>
        <p class="project__summary">{{ $portfolio->summary }}</p>
        <p class="project__meta">
            <span class="ledger__cat">{{ $portfolio->category }}</span>
            <time datetime="{{ $portfolio->date }}">{{ \Illuminate\Support\Carbon::parse($portfolio->date)->format('F Y') }}</time>
        </p>
        @if ($portfolio->link)
            <a class="btn btn--signal btn--lg" href="{{ $portfolio->link }}" rel="noopener">
                @if (str_contains($portfolio->link, 'github')) View on GitHub
                @elseif (str_contains($portfolio->link, 'youtube')) Watch the demo
                @else Visit project
                @endif
                <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M7 13l6-6M8 7h5v5"/></svg>
            </a>
        @endif
    </header>

    @if ($portfolio->image)
        <figure class="project__figure">
            <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}">
        </figure>
    @endif

    <div class="project__body">
        <div class="project__text">{{ $portfolio->description }}</div>
        @if ($tags)
            <aside class="project__tags" aria-label="Technologies">
                <h2 class="tape tape--small">Stack</h2>
                <ul>
                    @foreach ($tags as $tag)
                        <li><a href="{{ route('portfolio', ['search' => $tag]) }}">{{ $tag }}</a></li>
                    @endforeach
                </ul>
            </aside>
        @endif
    </div>


    <aside class="project__next">
        <p>Want the full picture?</p>
        <a class="btn btn--ink btn--lg" href="{{ route('cv') }}">View CV</a>
        <a class="btn btn--line btn--lg" href="{{ route('home') }}#contact">Get in touch</a>
    </aside>
</article>
@endsection
