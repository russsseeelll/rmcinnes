@extends('layouts.app', ['title' => ($portfolio->exists ? 'Edit '.$portfolio->title : 'New project').' · '.$profile->name])

@php($categories = \App\Models\Portfolio::query()->distinct()->orderBy('category')->pluck('category'))

@section('content')
<section class="page-head">
    <h1 class="tape tape--hero">{{ $portfolio->exists ? 'Edit project' : 'New project' }}</h1>
    @if ($portfolio->exists)<p class="page-head__sub">Editing “{{ $portfolio->title }}”.</p>@endif
</section>

<section class="section section--tight">
    <form class="form form--wide" method="POST" enctype="multipart/form-data"
          action="{{ $portfolio->exists ? route('portfolio.update', $portfolio->slug) : route('portfolio.store') }}">
        @csrf
        @if ($portfolio->exists) @method('PUT') @endif
        @if ($errors->any())
            <p class="form__error" role="alert">Some fields need fixing. They're marked below.</p>
        @endif

        @foreach ([
            ['title', 'Title', 'text', null],
            ['date', 'Date', 'date', null],
            ['category', 'Category', 'text', 'Pick an existing one or type a new one'],
            ['tags', 'Tags', 'text', 'Comma separated, e.g. Kubernetes, ArgoCD'],
            ['link', 'Link', 'url', 'GitHub, demo or live site (optional)'],
        ] as [$name, $label, $type, $note])
            <div class="field">
                <label for="f-{{ $name }}">{{ $label }} @if ($note)<span class="field__note">{{ $note }}</span>@endif</label>
                <input id="f-{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $portfolio->$name) }}"
                    @if ($name === 'category') list="categories" @endif
                    @error($name) aria-invalid="true" aria-describedby="f-{{ $name }}-err" @enderror>
                @error($name)<p class="field__err" id="f-{{ $name }}-err">{{ $message }}</p>@enderror
            </div>
        @endforeach
        <datalist id="categories">@foreach ($categories as $c)<option value="{{ $c }}">@endforeach</datalist>

        @foreach ([['summary', 'Summary', 3, 'Shown in project lists, up to 512 characters'], ['description', 'Description', 8, 'Shown on the project page, up to 3000 characters']] as [$name, $label, $rows, $note])
            <div class="field">
                <label for="f-{{ $name }}">{{ $label }} <span class="field__note">{{ $note }}</span></label>
                <textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
                    @error($name) aria-invalid="true" aria-describedby="f-{{ $name }}-err" @enderror>{{ old($name, $portfolio->$name) }}</textarea>
                @error($name)<p class="field__err" id="f-{{ $name }}-err">{{ $message }}</p>@enderror
            </div>
        @endforeach

        <div class="field">
            <label for="f-image">{{ $portfolio->image ? 'Replace image' : 'Image' }} <span class="field__note">JPG, PNG, GIF or WebP, up to 10 MB</span></label>
            @if ($portfolio->image)
                <div class="field__current">
                    <img src="{{ asset($portfolio->image) }}" alt="Current image for {{ $portfolio->title }}">
                    <label class="check"><input type="checkbox" name="remove_image" value="1" @checked(old('remove_image'))> Remove this image</label>
                </div>
            @endif
            <input id="f-image" type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                @error('image') aria-invalid="true" aria-describedby="f-image-err" @enderror>
            @error('image')<p class="field__err" id="f-image-err">{{ $message }}</p>@enderror
        </div>

        <div class="form__actions">
            <button type="submit" class="btn btn--signal btn--lg">{{ $portfolio->exists ? 'Save changes' : 'Add project' }}</button>
            <a class="btn btn--line btn--lg" href="{{ $portfolio->exists ? route('portfolio.single', $portfolio->slug) : route('portfolio') }}">Cancel</a>
        </div>
    </form>
</section>
@endsection
