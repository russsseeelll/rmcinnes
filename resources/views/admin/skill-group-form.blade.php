@extends('layouts.app', ['title' => ($group->exists ? 'Edit skill group' : 'New skill group').' · '.$profile->name])

@section('content')
<section class="admin-page">
    <header class="admin-page__head">
        <a class="arrowlink arrowlink--back" href="{{ route('home') }}#toolkit">
            <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg>
            Back to toolkit
        </a>
        <h1 class="admin-page__title">{{ $group->exists ? 'Edit skill group' : 'New skill group' }}</h1>
    </header>

    <form class="admin-form" method="POST" novalidate data-api-form
          action="{{ $group->exists ? url('/api/skill-groups/'.$group->id) : url('/api/skill-groups') }}"
          data-method="{{ $group->exists ? 'PUT' : 'POST' }}"
          data-success="{{ $group->exists ? 'kit-saved' : 'kit-added' }}">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden>Some fields need fixing. They're marked below.</p>

        <fieldset class="admin-form__group">
            <legend>Group</legend>
            <div class="field">
                <label for="k-name">Name</label>
                <input id="k-name" type="text" name="name" value="{{ $group->name }}" required maxlength="255" placeholder="Platform">
            </div>
            <div class="field">
                <label for="k-order">Position <span class="field__note">lower numbers show first</span></label>
                <input id="k-order" type="number" name="sort_order" min="0" value="{{ $group->sort_order ?? '' }}"
                    placeholder="{{ $group->exists ? '' : 'Added at the end' }}">
            </div>
            <div class="field field--wide">
                <label for="k-skills">Skills <span class="field__note">separate with commas or new lines; short names (12 characters or fewer) also appear as blocks in the game</span></label>
                <textarea id="k-skills" name="skills" rows="6" required maxlength="2000" placeholder="Kubernetes, ArgoCD, Helm">{{ $group->skills }}</textarea>
            </div>
        </fieldset>

        <div class="admin-form__actions">
            <button type="submit" class="btn btn--signal btn--lg" data-submit>{{ $group->exists ? 'Save changes' : 'Add group' }}</button>
            <a class="btn btn--line btn--lg" href="{{ route('home') }}#toolkit">Cancel</a>
            @if ($group->exists)
                <button type="button" class="btn btn--danger btn--lg admin-form__delete"
                    data-api-delete="{{ url('/api/skill-groups/'.$group->id) }}" data-title="{{ $group->name }}"
                    data-redirect="{{ route('home') }}#toolkit" data-scope="toolkit">Delete group</button>
            @endif
        </div>
    </form>
</section>
@endsection
