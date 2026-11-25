@extends('layouts.app', ['title' => 'Log in · '.$profile->name])

@section('content')
<section class="page-head">
    <h1 class="tape tape--hero">Log in</h1>
    <p class="page-head__sub">For managing projects on this site.</p>
</section>

<section class="section section--tight">
    <form class="form form--narrow" method="POST" action="{{ route('login') }}">
        @csrf
        @error('name')<p class="field__err" role="alert">{{ $message }}</p>@enderror
        <div class="field">
            <label for="l-name">Username</label>
            <input id="l-name" type="text" name="name" autocomplete="username" value="{{ old('name') }}" required>
        </div>
        <div class="field">
            <label for="l-pass">Password</label>
            <input id="l-pass" type="password" name="password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn btn--signal btn--lg">Log in</button>
    </form>
</section>
@endsection
