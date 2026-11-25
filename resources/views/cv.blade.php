@extends('layouts.app', ['title' => 'CV · '.$profile->name])

@php($host = fn ($url) => preg_replace('#^https?://(www\.)?#', '', rtrim($url, '/')))

@section('content')
<section class="cv-page">
    <div class="cv-actions">
        <button type="button" class="btn btn--signal btn--lg" onclick="window.print()">Save as PDF</button>
        @if ($profile->cvUrl())
            <a class="btn btn--line btn--lg" href="{{ $profile->cvUrl() }}" download="{{ $profile->cvDownloadName() }}">Original PDF</a>
        @endif
        <p class="cv-actions__note">Always up to date: generated from the same data as this site.</p>
    </div>

    <article class="cv-sheet">
        <header class="cv-head">
            <h1>{{ $profile->name }}</h1>
            <p class="cv-head__role">{{ $profile->headline }}</p>
            <p class="cv-head__contact">
                {{ collect([
                    $profile->location,
                    $profile->email,
                    $profile->linkedin_url ? $host($profile->linkedin_url) : null,
                    $profile->github_url ? $host($profile->github_url) : null,
                ])->filter()->implode(' · ') }}
            </p>
        </header>

        <p class="cv-intro">{{ $profile->intro }}</p>

        <section class="cv-section">
            <h2>Experience</h2>
            @foreach ($experiences as $job)
                <div class="cv-item">
                    <div class="cv-item__head">
                        <h3>{{ $job->title }} <span>· {{ $job->organisation }}</span></h3>
                        <p class="cv-item__when">{{ $job->period() }}</p>
                    </div>
                    @if ($job->highlightList())
                        <ul>@foreach ($job->highlightList() as $line)<li>{{ $line }}</li>@endforeach</ul>
                    @endif
                </div>
            @endforeach
        </section>

        <section class="cv-section">
            <h2>Education and certification</h2>
            @foreach ($education as $item)
                <div class="cv-item cv-item--row">
                    <h3>{{ $item->qualification }}{{ $item->grade && ! $item->in_progress && $item->grade !== 'Certification' ? ', '.$item->grade : '' }}
                        @if ($item->institution)<span>· {{ $item->institution }}</span>@endif</h3>
                    <p class="cv-item__when">{{ $item->period }}</p>
                </div>
            @endforeach
        </section>

        <section class="cv-section">
            <h2>Skills</h2>
            @foreach ($skillGroups as $group)
                <p class="cv-skills"><strong>{{ $group->name }}</strong> {{ $group->skills }}</p>
            @endforeach
        </section>
    </article>
</section>
@endsection
