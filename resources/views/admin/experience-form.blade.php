@extends('layouts.app', ['title' => ($experience->exists ? 'Edit role' : 'New role').' · '.$profile->name])

@section('content')
<section class="admin-page">
    <header class="admin-page__head">
        <a class="arrowlink arrowlink--back" href="{{ route('home') }}#experience">
            <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg>
            Back to experience
        </a>
        <h1 class="admin-page__title">{{ $experience->exists ? 'Edit role' : 'New role' }}</h1>
        @if ($experience->exists)<p class="admin-page__sub">{{ $experience->title }}, {{ $experience->organisation }}</p>@endif
    </header>

    <form class="admin-form" method="POST" novalidate data-api-form
          action="{{ $experience->exists ? url('/api/experience/'.$experience->id) : url('/api/experience') }}"
          data-method="{{ $experience->exists ? 'PUT' : 'POST' }}"
          data-success="{{ $experience->exists ? 'role-saved' : 'role-added' }}">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden>Some fields need fixing. They're marked below.</p>

        <fieldset class="admin-form__group">
            <legend>The role</legend>
            <div class="field">
                <label for="e-title">Job title</label>
                <input id="e-title" type="text" name="title" value="{{ $experience->title }}" required maxlength="255">
            </div>
            <div class="field">
                <label for="e-org">Organisation</label>
                <input id="e-org" type="text" name="organisation" value="{{ $experience->organisation }}" required maxlength="255">
            </div>
            <div class="field">
                <label for="e-start">Started</label>
                <input id="e-start" type="date" name="started_on" value="{{ $experience->started_on?->toDateString() }}" required>
            </div>
            <div class="field">
                <label for="e-end">Ended <span class="field__note">leave empty if you're still there</span></label>
                <input id="e-end" type="date" name="ended_on" value="{{ $experience->ended_on?->toDateString() }}">
            </div>
        </fieldset>

        <fieldset class="admin-form__group">
            <legend>Highlights</legend>
            <div class="field field--wide">
                <label for="e-highlights">What you did <span class="field__note">one per line · <span data-count-for="e-highlights"></span>/5000</span></label>
                <textarea id="e-highlights" name="highlights" rows="12" maxlength="5000">{{ $experience->highlights }}</textarea>
            </div>
        </fieldset>

        <div class="admin-form__actions">
            <button type="submit" class="btn btn--signal btn--lg" data-submit>{{ $experience->exists ? 'Save changes' : 'Add role' }}</button>
            <a class="btn btn--line btn--lg" href="{{ route('home') }}#experience">Cancel</a>
            @if ($experience->exists)
                <button type="button" class="btn btn--danger btn--lg admin-form__delete"
                    data-api-delete="{{ url('/api/experience/'.$experience->id) }}" data-title="{{ $experience->title }}"
                    data-redirect="{{ route('home') }}#experience" data-scope="experience">Delete role</button>
            @endif
        </div>
    </form>
</section>
@endsection
