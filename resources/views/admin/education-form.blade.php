@extends('layouts.app', ['title' => ($education->exists ? 'Edit qualification' : 'New qualification').' · '.$profile->name])

@section('content')
<section class="admin-page">
    <header class="admin-page__head">
        <a class="arrowlink arrowlink--back" href="{{ route('home') }}#education">
            <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg>
            Back to education
        </a>
        <h1 class="admin-page__title">{{ $education->exists ? 'Edit qualification' : 'New qualification' }}</h1>
        @if ($education->exists)<p class="admin-page__sub">{{ $education->qualification }}</p>@endif
    </header>

    <form class="admin-form" method="POST" novalidate data-api-form
          action="{{ $education->exists ? url('/api/education/'.$education->id) : url('/api/education') }}"
          data-method="{{ $education->exists ? 'PUT' : 'POST' }}"
          data-success="{{ $education->exists ? 'edu-saved' : 'edu-added' }}">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden>Some fields need fixing. They're marked below.</p>

        <fieldset class="admin-form__group">
            <legend>Qualification or certification</legend>
            <div class="field field--wide">
                <label for="d-qual">Name</label>
                <input id="d-qual" type="text" name="qualification" value="{{ $education->qualification }}" required maxlength="255"
                    placeholder="BSc (Hons) Software Engineering">
            </div>
            <div class="field">
                <label for="d-inst">Institution <span class="field__note">optional</span></label>
                <input id="d-inst" type="text" name="institution" value="{{ $education->institution }}" maxlength="255">
            </div>
            <div class="field">
                <label for="d-period">Dates <span class="field__note">optional, as you'd write them</span></label>
                <input id="d-period" type="text" name="period" value="{{ $education->period }}" maxlength="255" placeholder="2023 – 2025">
            </div>
            <div class="field">
                <label for="d-grade">Grade or status <span class="field__note">optional</span></label>
                <input id="d-grade" type="text" name="grade" value="{{ $education->grade }}" maxlength="255" placeholder="First Class Honours">
            </div>
            <div class="field">
                <label for="d-order">Position <span class="field__note">lower numbers show first</span></label>
                <input id="d-order" type="number" name="sort_order" min="0" value="{{ $education->sort_order ?? '' }}"
                    placeholder="{{ $education->exists ? '' : 'Added at the end' }}">
            </div>
            <div class="field field--wide admin-form__checks">
                <label class="check"><input type="checkbox" name="featured" value="1" @checked($education->featured)> Feature it <span class="field__note">shown large and used as "Degree" in the intro</span></label>
                <label class="check"><input type="checkbox" name="in_progress" value="1" @checked($education->in_progress)> Still studying <span class="field__note">shown as "Studying" in the intro</span></label>
            </div>
        </fieldset>

        <div class="admin-form__actions">
            <button type="submit" class="btn btn--signal btn--lg" data-submit>{{ $education->exists ? 'Save changes' : 'Add qualification' }}</button>
            <a class="btn btn--line btn--lg" href="{{ route('home') }}#education">Cancel</a>
            @if ($education->exists)
                <button type="button" class="btn btn--danger btn--lg admin-form__delete"
                    data-api-delete="{{ url('/api/education/'.$education->id) }}" data-title="{{ $education->qualification }}"
                    data-redirect="{{ route('home') }}#education" data-scope="education">Delete</button>
            @endif
        </div>
    </form>
</section>
@endsection
