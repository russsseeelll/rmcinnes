@extends('layouts.app', ['title' => 'Projects · '.$profile->name])

@section('content')
<section class="page-head">
    <h1 class="tape tape--hero">Projects</h1>
    <p class="page-head__sub">Infrastructure, automation and software projects I've built or deployed, newest first.</p>
</section>

<section class="section section--tight" aria-label="Project list">
    <form class="filter" method="GET" action="{{ route('portfolio') }}" role="search">
        <label class="visually-hidden" for="q">Search projects</label>
        <input id="q" type="search" name="search" value="{{ request('search') }}" placeholder="Search by name, tag or category">
        @if (request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
        <button type="submit" class="btn btn--ink">Search</button>
        <nav class="filter__cats" aria-label="Category">
            @foreach (['' => 'All'] + $categories->mapWithKeys(fn ($c) => [$c => $c])->all() as $value => $label)
                <a href="{{ route('portfolio', array_filter(['category' => $value, 'search' => request('search')])) }}"
                   @if ((string) request('category') === $value) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
    </form>

    @if ($message = session('message'))
        <p class="form__ok" role="status">{{ $message }}</p>
    @endif

    @if ($portfolios->isEmpty())
        <div class="empty">
            <p>No projects match{{ request('search') ? ' “'.request('search').'”' : '' }}.</p>
            <a class="arrowlink" href="{{ route('portfolio') }}">Clear filters <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M4 10h12m-5-5 5 5-5 5"/></svg></a>
        </div>
    @else
        <ul class="ledger">
            @foreach ($portfolios as $p)
                @include('partials.project-row', ['p' => $p])
            @endforeach
        </ul>
    @endif

    @if ($portfolios->hasPages())
        <nav class="pager" aria-label="Pages">
            @if ($portfolios->onFirstPage())
                <span aria-disabled="true"><svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg> Newer</span>
            @else
                <a href="{{ $portfolios->previousPageUrl() }}" rel="prev"><svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg> Newer</a>
            @endif
            <span class="pager__count">Page {{ $portfolios->currentPage() }} of {{ $portfolios->lastPage() }}</span>
            @if ($portfolios->hasMorePages())
                <a href="{{ $portfolios->nextPageUrl() }}" rel="next">Older <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M4 10h12m-5-5 5 5-5 5"/></svg></a>
            @else
                <span aria-disabled="true">Older <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M4 10h12m-5-5 5 5-5 5"/></svg></span>
            @endif
        </nav>
    @endif

    @auth
        <p class="admin-add"><a class="btn btn--line" href="{{ route('portfolio.create') }}">Add project</a></p>
    @endauth
</section>
@endsection
