@extends('layouts.app', ['title' => 'Edit profile · '.$profile->name])

@section('content')
<section class="admin-page">
    <header class="admin-page__head">
        <a class="arrowlink arrowlink--back" href="{{ route('home') }}">
            <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg>
            Back to the homepage
        </a>
        <h1 class="admin-page__title">Edit profile</h1>
        <p class="admin-page__sub">Your name, intro and contact details, used across the whole site and the CV page.</p>
    </header>

    <form class="admin-form" method="POST" enctype="multipart/form-data" novalidate data-api-form
          action="{{ url('/api/profile') }}" data-method="PUT" data-success="profile-saved">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden>Some fields need fixing. They're marked below.</p>

        <fieldset class="admin-form__group">
            <legend>Who you are</legend>
            <div class="field">
                <label for="p-name">Name</label>
                <input id="p-name" type="text" name="name" value="{{ $profile->exists ? $profile->name : '' }}" required maxlength="255">
            </div>
            <div class="field">
                <label for="p-location">Location <span class="field__note">optional</span></label>
                <input id="p-location" type="text" name="location" value="{{ $profile->location }}" maxlength="255">
            </div>
            <div class="field field--wide">
                <label for="p-headline">Headline <span class="field__note">the role shown under your name</span></label>
                <input id="p-headline" type="text" name="headline" value="{{ $profile->exists ? $profile->headline : '' }}" required maxlength="255">
            </div>
            <div class="field field--wide">
                <label for="p-intro">Intro <span class="field__note">the paragraph under the game · <span data-count-for="p-intro"></span>/2000</span></label>
                <textarea id="p-intro" name="intro" rows="5" required maxlength="2000">{{ $profile->exists ? $profile->intro : '' }}</textarea>
            </div>
            <div class="field field--wide">
                <label for="p-ai">Summary for AI tools <span class="field__note">published in <a href="{{ url('/llms.txt') }}">/llms.txt</a> for AI assistants and search; write it in the first person · <span data-count-for="p-ai"></span>/3000</span></label>
                <textarea id="p-ai" name="ai_summary" rows="8" maxlength="3000">{{ $profile->ai_summary }}</textarea>
            </div>
        </fieldset>

        <fieldset class="admin-form__group">
            <legend>Contact</legend>
            <div class="field">
                <label for="p-email">Email <span class="field__note">shown on the site; contact form messages go here</span></label>
                <input id="p-email" type="email" name="email" value="{{ $profile->email }}" required maxlength="255">
            </div>
            <div class="field">
                <label for="p-contact">Contact heading <span class="field__note">optional</span></label>
                <input id="p-contact" type="text" name="contact_heading" value="{{ $profile->contact_heading }}" maxlength="255" placeholder="Get in touch">
            </div>
            <div class="field">
                <label for="p-github">GitHub URL <span class="field__note">optional</span></label>
                <input id="p-github" type="url" name="github_url" value="{{ $profile->github_url }}" maxlength="255">
            </div>
            <div class="field">
                <label for="p-linkedin">LinkedIn URL <span class="field__note">optional</span></label>
                <input id="p-linkedin" type="url" name="linkedin_url" value="{{ $profile->linkedin_url }}" maxlength="255">
            </div>
        </fieldset>

        <fieldset class="admin-form__group">
            <legend>Search engines</legend>
            <div class="field field--wide">
                <label for="p-meta">Search description <span class="field__note">optional, shown by Google under your name</span></label>
                <input id="p-meta" type="text" name="meta_description" value="{{ $profile->meta_description }}" maxlength="255">
            </div>
        </fieldset>

        <fieldset class="admin-form__group">
            <legend>Extras</legend>
            <div class="field field--wide admin-form__checks">
                <label class="check"><input type="checkbox" name="show_mascot" value="1" @checked($profile->show_mascot ?? true)>
                    Show the spaniel <span class="field__note">the pixel-art dog next to your name in the top bar</span></label>
            </div>
        </fieldset>

        <div class="admin-form__actions">
            <button type="submit" class="btn btn--signal btn--lg" data-submit>Save profile</button>
            <a class="btn btn--line btn--lg" href="{{ route('home') }}">Cancel</a>
        </div>
    </form>
</section>
@endsection
