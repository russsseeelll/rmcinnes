@extends('layouts.app', ['title' => 'Leaderboard · '.$profile->name])

@section('content')
<section class="admin-page">
    <header class="admin-page__head">
        <a class="arrowlink arrowlink--back" href="{{ route('home') }}">
            <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg>
            Back to the homepage
        </a>
        <h1 class="admin-page__title">Leaderboard</h1>
        <p class="admin-page__sub">The scores shown when someone finishes the cluster game. The top ten are shown.</p>
    </header>

    @include('partials.flash', ['scope' => 'leaderboard'])

    <div class="admin-form board-admin">
        @forelse ($scores as $s)
            <form class="board-admin__row" method="POST" novalidate data-api-form action="{{ url('/api/scores/'.$s->id) }}" data-method="PUT" data-success="score-saved">
                <span class="board-admin__rank">{{ $loop->iteration }}</span>
                <label class="visually-hidden" for="n-{{ $s->id }}">Name</label>
                <input id="n-{{ $s->id }}" name="name" value="{{ $s->name }}" maxlength="40" required>
                <label class="visually-hidden" for="o-{{ $s->id }}">Company</label>
                <input id="o-{{ $s->id }}" name="organisation" value="{{ $s->organisation }}" maxlength="40" placeholder="Company">
                <label class="visually-hidden" for="s-{{ $s->id }}">Score</label>
                <input id="s-{{ $s->id }}" name="score" type="number" min="0" max="999" value="{{ $s->score }}" required>
                <button type="submit" class="btn btn--ink btn--sm" data-submit>Save</button>
                <button type="button" class="btn btn--danger btn--sm" data-api-delete="{{ url('/api/scores/'.$s->id) }}"
                    data-title="{{ $s->name }}" data-redirect="{{ route('leaderboard.edit') }}" data-scope="leaderboard">Delete</button>
                <p class="form__error board-admin__error" data-form-error role="alert" tabindex="-1" hidden></p>
            </form>
        @empty
            <p class="admin-form__hint">No entries yet.</p>
        @endforelse
    </div>

    <form class="admin-form board-admin board-admin--new" method="POST" novalidate data-api-form action="{{ url('/api/scores') }}" data-method="POST" data-success="score-added">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden>Some fields need fixing. They're marked below.</p>
        <fieldset class="admin-form__group">
            <legend>Add an entry</legend>
            <div class="field">
                <label for="new-name">Name</label>
                <input id="new-name" name="name" maxlength="40" required>
            </div>
            <div class="field">
                <label for="new-org">Company <span class="field__note">optional</span></label>
                <input id="new-org" name="organisation" maxlength="40">
            </div>
            <div class="field">
                <label for="new-score">Score</label>
                <input id="new-score" name="score" type="number" min="0" max="999" required>
            </div>
        </fieldset>
        <div class="admin-form__actions">
            <button type="submit" class="btn btn--signal btn--lg" data-submit>Add entry</button>
        </div>
    </form>
</section>
@endsection
