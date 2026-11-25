<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $profile->name.' · '.$profile->headline }}</title>
    @if ($profile->meta_description)<meta name="description" content="{{ $profile->meta_description }}">@endif
    <meta name="theme-color" content="#252425">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,100..900&family=Martian+Mono:wdth,wght@87.5,400..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip" href="#main">Skip to content</a>

    <header class="rail">
        <a href="{{ route('home') }}" class="tape tape--small rail__home" aria-label="{{ $profile->name }}, home">{{ $profile->shortName() }}</a>
        <nav class="rail__nav" aria-label="Main">
            <a href="{{ route('home') }}#work" @class(['is-current' => request()->routeIs('portfolio', 'portfolio.single')])>Projects</a>
            <a href="{{ route('home') }}#experience">Experience</a>
            <a href="{{ route('home') }}#contact">Contact</a>
            @auth<a href="{{ route('portfolio.create') }}" @class(['is-current' => request()->routeIs('portfolio.create')])>Add project</a>@endauth
            <a @class(['btn btn--ink', 'is-current' => request()->routeIs('cv')]) href="{{ route('cv') }}">CV</a>
        </nav>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="foot">
        <div class="foot__inner">
            <span class="tape tape--dark tape--small">{{ request()->getHost() }}</span>
            <p class="foot__meta">{{ collect([$profile->name, $profile->location])->filter()->implode(' · ') }} · &copy; {{ date('Y') }}</p>
            @if ($latestDeploy)
                <p class="foot__deploy">
                    Deployed <time datetime="{{ $latestDeploy->deployed_at->toIso8601String() }}" title="{{ $latestDeploy->deployed_at->toDayDateTimeString() }}">{{ $latestDeploy->deployed_at->diffForHumans() }}</time>
                    from <code>{{ $latestDeploy->shortCommit() }}</code>@if ($latestDeploy->duration()) · pipeline {{ $latestDeploy->duration() }}@endif
                    @if ($latestDeploy->run_url) · <a href="{{ $latestDeploy->run_url }}" rel="noopener">view run</a>@endif
                </p>
            @endif
            <ul class="foot__links">
                <li><a href="{{ route('status') }}">Status</a></li>
                <li><a href="mailto:{{ $profile->email }}">Email</a></li>
                @if ($profile->github_url)<li><a href="{{ $profile->github_url }}" rel="me">GitHub</a></li>@endif
                @if ($profile->linkedin_url)<li><a href="{{ $profile->linkedin_url }}" rel="me">LinkedIn</a></li>@endif
                @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="linklike">Log out</button></form>
                </li>
                @endauth
            </ul>
        </div>
    </footer>
</body>
</html>
