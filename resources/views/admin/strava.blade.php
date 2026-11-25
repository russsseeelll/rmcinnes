@extends('layouts.app', ['title' => 'Strava · '.$profile->name])

@php
    $labels = ['client_id' => 'Client ID', 'client_secret' => 'Client secret', 'refresh_token' => 'Refresh token'];
    $running = $strava->running();
@endphp

@section('content')
<section class="admin-page">
    <header class="admin-page__head">
        <a class="arrowlink arrowlink--back" href="{{ route('extras') }}">
            <svg class="ico" aria-hidden="true" viewBox="0 0 20 20" width="16" height="16"><path d="M16 10H4m5-5-5 5 5 5"/></svg>
            Back to Extras
        </a>
        <h1 class="admin-page__title">Strava</h1>
        <p class="admin-page__sub">Connects the running stats on the Extras page. Values in <code>.env</code> take priority; anything missing there is read from here.</p>
    </header>

    @include('partials.flash', ['scope' => 'strava'])

    <form class="admin-form" method="POST" novalidate data-api-form action="{{ url('/api/strava') }}" data-method="PUT" data-success="strava-saved">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden>Some fields need fixing. They're marked below.</p>

        <fieldset class="admin-form__group">
            <legend>API application</legend>
            @foreach ($labels as $key => $label)
                @php($source = $strava->source($key))
                <div @class(['field', 'field--wide' => $key === 'refresh_token'])>
                    <label for="s-{{ $key }}">{{ $label }}
                        <span class="field__note">
                            @if ($source === 'env') set in .env
                            @elseif ($source === 'database') saved{{ $key === 'client_id' ? '' : ', leave empty to keep' }}
                            @else not set
                            @endif
                        </span>
                    </label>
                    <input id="s-{{ $key }}" name="{{ $key }}" autocomplete="off" maxlength="255"
                        type="{{ $key === 'client_id' ? 'text' : 'password' }}"
                        value="{{ $key === 'client_id' && $source === 'database' ? $strava->credential('client_id') : '' }}"
                        placeholder="{{ $source === 'env' ? 'Set in .env' : ($source === 'database' && $key !== 'client_id' ? '••••••••' : '') }}"
                        @disabled($source === 'env')>
                </div>
            @endforeach
            <p class="field--wide admin-form__hint">Find these under your API application at <a href="https://www.strava.com/settings/api" rel="noopener">strava.com/settings/api</a>. Secrets are stored encrypted and never shown again.</p>
        </fieldset>

        <div class="admin-form__actions">
            <button type="submit" class="btn btn--signal btn--lg" data-submit>Save</button>
            @if ($strava->source('client_id') === 'database' || $strava->source('client_secret') === 'database' || $strava->source('refresh_token') === 'database')
                <button type="button" class="btn btn--danger btn--lg admin-form__delete" data-api-delete="{{ url('/api/strava') }}"
                    data-title="Strava connection" data-redirect="{{ route('strava.edit') }}" data-scope="strava">Disconnect</button>
            @endif
        </div>
    </form>

    <form class="admin-form strava-sync" method="POST" novalidate data-api-form action="{{ url('/api/strava/sync') }}" data-method="POST" data-success="strava-synced">
        <p class="form__error" data-form-error role="alert" tabindex="-1" hidden></p>
        <fieldset class="admin-form__group">
            <legend>Running stats</legend>
            <div class="field--wide">
                @if ($running)
                    <p class="strava-sync__now">{{ rtrim(rtrim(number_format($running['km'], 1), '0'), '.') }} km over {{ $running['runs'] }} runs in {{ $running['year'] }},
                        averaging {{ \App\Services\Strava::formatPace($running['pace_seconds_per_km']) ?? '–' }} /km.</p>
                    <p class="admin-form__hint">Last synced {{ \Illuminate\Support\Carbon::parse($running['fetched_at'])->diffForHumans() }}. Syncs hourly on its own.</p>
                @else
                    <p class="admin-form__hint">{{ $strava->configured() ? 'Not synced yet.' : 'Add the details above, then sync.' }}</p>
                @endif
            </div>
        </fieldset>
        <div class="admin-form__actions">
            <button type="submit" class="btn btn--ink btn--lg" data-submit @disabled(! $strava->configured())>Sync now</button>
        </div>
    </form>
</section>
@endsection
