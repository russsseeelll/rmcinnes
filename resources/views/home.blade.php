@extends('layouts.app')

@php
    $featured = $education->firstWhere('featured', true);
    $studying = $education->firstWhere('in_progress', true);
    $current = $experiences->first();
    $workloads = $skillGroups->flatMap->skillList()
        ->map(fn ($s) => strtolower(preg_replace(['/\s*\/\s*/', '/\s+/'], ['/', '-'], $s)))
        ->filter(fn ($s) => mb_strlen($s) <= 12)->values();
    $colours = ['periwinkle', 'lavender', 'amber', 'mint', 'tomato'];
@endphp

@section('content')
<section class="hero" aria-labelledby="hero-name">

    <div class="cluster" data-cluster data-workloads='@json($workloads)'>
        <div class="hero__labels">
            <h1 id="hero-name" class="tape tape--hero">{{ $profile->name }}</h1>
            <p class="tape tape--dark tape--role"><span class="nw">{{ $profile->headline }}</span>@if ($profile->location)<span class="tape__sep" aria-hidden="true">·</span><span class="nw">{{ $profile->location }}</span>@endif</p>
        </div>
        <div class="cluster__nodes" aria-hidden="true" data-cluster-nodes></div>
        <canvas class="cluster__board" data-cluster-canvas tabindex="-1"
            role="img" aria-label="A cluster of three server nodes. Labelled workloads drop in and pack together; every full row is a shipped rollout."></canvas>

        <div class="cluster__overlay" data-cluster-overlay hidden>
            <p class="cluster__overlay-title">Cluster at capacity</p>
            <p class="cluster__overlay-sub"><span data-cluster-final>0</span> rollouts shipped before the OOM killer arrived.</p>
            <p class="board-title" data-board-title hidden>Leaderboard</p>
            <ol class="board" data-board></ol>
            <button type="button" class="btn btn--signal" data-cluster-restart>Redeploy</button>
        </div>

        <div class="cluster__rail">
            <button type="button" class="btn btn--signal" data-cluster-toggle aria-pressed="false">
                <svg aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M6 4v12l10-6z" fill="currentColor"/></svg>
                <span data-cluster-toggle-label>Take the controls</span>
            </button>
            <p class="cluster__hint" data-cluster-hint>
                <kbd>←</kbd><kbd>→</kbd> move <kbd>↑</kbd> rotate <kbd>↓</kbd> faster <kbd>Space</kbd> drop
            </p>
            <p class="cluster__best" data-cluster-best hidden></p>
            <p class="cluster__score"><span class="cluster__score-label">rollouts shipped</span> <output data-cluster-score>000</output></p>
        </div>

        <div class="cluster__pad" data-cluster-pad hidden>
            <button type="button" data-cmd="left" aria-label="Move left"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 5l-7 7 7 7" /></svg></button>
            <button type="button" data-cmd="rotate" aria-label="Rotate"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12a7 7 0 1 1-2.05-4.95M19 4v4h-4" /></svg></button>
            <button type="button" data-cmd="drop" aria-label="Drop"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v13m0 0-5-5m5 5 5-5M5 20h14" /></svg></button>
            <button type="button" data-cmd="right" aria-label="Move right"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7" /></svg></button>
        </div>
    </div>

    <div class="hero__actions">
        <a class="btn btn--ink btn--lg" href="{{ route('cv') }}">View CV</a>
        <a class="btn btn--line btn--lg" href="#contact">Get in touch</a>
    </div>
</section>

<section class="intro" id="about" aria-label="About">
    @include('partials.flash', ['scope' => 'profile'])
    <p class="intro__lead">{{ $profile->intro }}</p>
    @auth<p><a class="btn btn--ink btn--sm" href="{{ route('profile.edit') }}">Edit intro and profile</a></p>@endauth
    <dl class="facts">
        @if ($current)<div><dt>Now</dt><dd>{{ $current->title }}, {{ $current->organisation }}</dd></div>@endif
        @if ($featured)<div><dt>Degree</dt><dd>{{ $featured->qualification }}{{ $featured->grade ? ', '.$featured->grade : '' }}</dd></div>@endif
        @if ($studying)<div><dt>Studying</dt><dd>{{ $studying->qualification }}</dd></div>@endif
    </dl>
</section>

<section class="section" id="experience" aria-labelledby="exp-h">
    <div class="section__head">
        <h2 id="exp-h" class="tape tape--section">Experience</h2>
        @auth<a class="btn btn--signal btn--sm" href="{{ route('experience.create') }}">Add role</a>@endauth
    </div>
    @include('partials.flash', ['scope' => 'experience'])
    <ol class="rack">
        @foreach ($experiences as $job)
            <li @class(['rack__unit', 'rack__unit--quiet' => ! $job->highlightList()])>
                <p class="rack__when">{{ $job->period() }}</p>
                <div class="rack__body">
                    <h3>{{ $job->title }} <span>{{ $job->organisation }}</span></h3>
                    @auth
                        <div class="row-admin">
                            <a class="btn btn--ink btn--sm" href="{{ route('experience.edit', $job) }}">Edit</a>
                            <button type="button" class="btn btn--danger btn--sm" data-api-delete="{{ url('/api/experience/'.$job->id) }}"
                                data-title="{{ $job->title }}" data-redirect="{{ route('home') }}#experience" data-scope="experience">Delete</button>
                        </div>
                    @endauth
                    @if ($job->highlightList())
                        <ul>
                            @foreach ($job->highlightList() as $line)<li>{{ $line }}</li>@endforeach
                        </ul>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</section>

<section class="section section--panel" id="toolkit" aria-labelledby="kit-h">
    <div class="section__head">
        <h2 id="kit-h" class="tape tape--section">Toolkit</h2>
        @auth<a class="btn btn--signal btn--sm" href="{{ route('toolkit.create') }}">Add group</a>@endauth
    </div>
    @include('partials.flash', ['scope' => 'toolkit'])
    <div class="kit">
        @foreach ($skillGroups as $group)
            <div class="kit__group" style="--cable: var(--{{ $colours[$loop->index % count($colours)] }})">
                <h3>{{ $group->name }}</h3>
                <ul>@foreach ($group->skillList() as $skill)<li>{{ $skill }}</li>@endforeach</ul>
                @auth
                    <div class="row-admin">
                        <a class="btn btn--ink btn--sm" href="{{ route('toolkit.edit', $group) }}">Edit</a>
                        <button type="button" class="btn btn--danger btn--sm" data-api-delete="{{ url('/api/skill-groups/'.$group->id) }}"
                            data-title="{{ $group->name }}" data-redirect="{{ route('home') }}#toolkit" data-scope="toolkit">Delete</button>
                    </div>
                @endauth
            </div>
        @endforeach
    </div>
</section>

<section class="section" id="work" aria-labelledby="work-h">
    <div class="section__head">
        <h2 id="work-h" class="tape tape--section">Projects</h2>
        <a class="arrowlink" href="{{ route('portfolio') }}">All projects <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M4 10h12m-5-5 5 5-5 5"/></svg></a>
    </div>
    @if ($projects->isEmpty())
        <p class="empty">Projects are on their way.@if ($profile->github_url) In the meantime, see <a href="{{ $profile->github_url }}">GitHub</a>.@endif</p>
    @else
        <ul class="ledger">
            @foreach ($projects as $p)
                @include('partials.project-row', ['p' => $p])
            @endforeach
        </ul>
    @endif
</section>

<section class="section section--panel" id="education" aria-labelledby="edu-h">
    <div class="section__head">
        <h2 id="edu-h" class="tape tape--section">Education</h2>
        @auth<a class="btn btn--signal btn--sm" href="{{ route('education.create') }}">Add qualification</a>@endauth
    </div>
    @include('partials.flash', ['scope' => 'education'])
    <ul class="edu">
        @foreach ($education->sortByDesc('featured') as $item)
            <li @class(['edu__item', 'edu__item--lead' => $item->featured])>
                <h3>{{ $item->qualification }}</h3>
                @if ($item->institution)<p>{{ $item->institution }}</p>@endif
                @if ($item->grade || $item->period)
                    <p class="edu__grade">{{ collect([$item->grade, $item->period])->filter()->implode(' · ') }}</p>
                @endif
                @auth
                    <div class="row-admin">
                        <a class="btn btn--ink btn--sm" href="{{ route('education.edit', $item) }}">Edit</a>
                        <button type="button" class="btn btn--danger btn--sm" data-api-delete="{{ url('/api/education/'.$item->id) }}"
                            data-title="{{ $item->qualification }}" data-redirect="{{ route('home') }}#education" data-scope="education">Delete</button>
                    </div>
                @endauth
            </li>
        @endforeach
    </ul>
</section>

<section class="contact" id="contact" aria-labelledby="contact-h">
    <div class="contact__lead">
        <h2 id="contact-h" class="contact__title">{{ $profile->contact_heading ?? 'Get in touch' }}</h2>
        <a class="contact__email" href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
        <p class="contact__links">
            @if ($profile->linkedin_url)<a href="{{ $profile->linkedin_url }}" rel="me">LinkedIn</a>@endif
            @if ($profile->github_url)<a href="{{ $profile->github_url }}" rel="me">GitHub</a>@endif
            <a href="{{ route('cv') }}">CV</a>
        </p>
    </div>

    <form class="form" method="POST" action="{{ url('/contact') }}" novalidate>
        @csrf
        @if (session('message_sent'))
            <p class="form__ok" role="status">{{ session('message_sent') }}</p>
        @endif
        <div class="field">
            <label for="c-name">Name</label>
            <input id="c-name" type="text" name="name" autocomplete="name" value="{{ old('name') }}" required
                @error('name') aria-invalid="true" aria-describedby="c-name-err" @enderror>
            @error('name')<p class="field__err" id="c-name-err">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="c-email">Email</label>
            <input id="c-email" type="email" name="email" autocomplete="email" value="{{ old('email') }}" required
                @error('email') aria-invalid="true" aria-describedby="c-email-err" @enderror>
            @error('email')<p class="field__err" id="c-email-err">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="c-msg">Message <span class="field__note">at least 20 characters</span></label>
            <textarea id="c-msg" name="message" rows="5" required
                @error('message') aria-invalid="true" aria-describedby="c-msg-err" @enderror>{{ old('message') }}</textarea>
            @error('message')<p class="field__err" id="c-msg-err">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="btn btn--signal btn--lg">Send message</button>
    </form>
</section>
@endsection
