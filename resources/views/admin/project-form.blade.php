@extends('layouts.app', ['title' => ($portfolio->exists ? 'Edit '.$portfolio->title : 'New project').' · '.$profile->name])

@php
    $categories = \App\Models\Portfolio::query()->distinct()->orderBy('category')->pluck('category');
    $action = $portfolio->exists ? url('/api/projects/'.$portfolio->slug) : url('/api/projects');
@endphp

@section('content')
<section class="admin-page">
    <header class="admin-page__head">
        <a class="arrowlink arrowlink--back" href="{{ $portfolio->exists ? route('portfolio.single', $portfolio->slug) : route('portfolio') }}">
            <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg>
            {{ $portfolio->exists ? 'Back to project' : 'All projects' }}
        </a>
        <h1 class="admin-page__title">{{ $portfolio->exists ? 'Edit project' : 'New project' }}</h1>
        @if ($portfolio->exists)<p class="admin-page__sub">Editing “{{ $portfolio->title }}”</p>@endif
    </header>

    <form class="admin-form" method="POST" enctype="multipart/form-data" novalidate
          data-api-form action="{{ $action }}" data-method="{{ $portfolio->exists ? 'PUT' : 'POST' }}"
          data-success="{{ $portfolio->exists ? 'saved' : 'added' }}">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden>Some fields need fixing. They're marked below.</p>

        <fieldset class="admin-form__group">
            <legend>The basics</legend>
            <div class="field field--wide">
                <label for="f-title">Title</label>
                <input id="f-title" type="text" name="title" value="{{ $portfolio->title }}" required maxlength="255">
            </div>
            <div class="field">
                <label for="f-date">Date</label>
                <input id="f-date" type="date" name="date" value="{{ $portfolio->date }}" required>
            </div>
            <div class="field">
                <label for="f-category">Category <span class="field__note">pick one or type a new one</span></label>
                <input id="f-category" type="text" name="category" value="{{ $portfolio->category }}" list="categories" required maxlength="255">
                <datalist id="categories">@foreach ($categories as $c)<option value="{{ $c }}">@endforeach</datalist>
            </div>
            <div class="field">
                <label for="f-tags">Tags <span class="field__note">comma separated</span></label>
                <input id="f-tags" type="text" name="tags" value="{{ $portfolio->tags }}" placeholder="Kubernetes, ArgoCD" maxlength="255">
            </div>
            <div class="field">
                <label for="f-link">Link <span class="field__note">optional</span></label>
                <input id="f-link" type="url" name="link" value="{{ $portfolio->link }}" placeholder="https://github.com/…" maxlength="255">
            </div>
        </fieldset>

        <fieldset class="admin-form__group">
            <legend>The write-up</legend>
            <div class="field field--wide">
                <label for="f-summary">Summary <span class="field__note">shown in project lists · <span data-count-for="f-summary"></span>/512</span></label>
                <textarea id="f-summary" name="summary" rows="3" required maxlength="512">{{ $portfolio->summary }}</textarea>
            </div>
            <div class="field field--wide">
                <label for="f-description">Description <span class="field__note">shown on the project page · <span data-count-for="f-description"></span>/3000</span></label>
                <textarea id="f-description" name="description" rows="10" required maxlength="3000">{{ $portfolio->description }}</textarea>
            </div>
        </fieldset>

        <fieldset class="admin-form__group">
            <legend>Image</legend>
            @if ($portfolio->image)
                <div class="field field--wide field__current">
                    <img src="{{ asset($portfolio->image) }}" alt="Current image for {{ $portfolio->title }}">
                    <label class="check"><input type="checkbox" name="remove_image" value="1"> Remove this image</label>
                </div>
            @endif
            <div class="field field--wide">
                <label for="f-image">{{ $portfolio->image ? 'Replace with' : 'Upload' }} <span class="field__note">JPG, PNG, GIF or WebP, up to 10 MB</span></label>
                <input id="f-image" type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            </div>
        </fieldset>

        <div class="admin-form__actions">
            <button type="submit" class="btn btn--signal btn--lg" data-submit>{{ $portfolio->exists ? 'Save changes' : 'Add project' }}</button>
            <a class="btn btn--line btn--lg" href="{{ $portfolio->exists ? route('portfolio.single', $portfolio->slug) : route('portfolio') }}">Cancel</a>
            @if ($portfolio->exists)
                <button type="button" class="btn btn--danger btn--lg admin-form__delete"
                    data-api-delete="{{ url('/api/projects/'.$portfolio->slug) }}" data-title="{{ $portfolio->title }}">Delete project</button>
            @endif
        </div>
    </form>
</section>
@endsection
