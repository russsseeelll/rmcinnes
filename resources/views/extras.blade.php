@extends('layouts.app', ['title' => 'Extras · '.$profile->name])

@section('content')
<section class="page-head">
    <h1 class="tape tape--hero">Extras</h1>
    <p class="page-head__sub">Things from outside work, and how this site is running.</p>
</section>

@if ($running)
<section class="section section--tight extras" id="running" aria-labelledby="running-h">
    <h2 id="running-h" class="tape tape--section">Running</h2>
    <article class="run-card">
        <h3 class="run-card__title">Running in {{ $running['year'] }}</h3>
        <dl class="run-card__stats">
            <div><dt>Distance this year</dt><dd>{{ rtrim(rtrim(number_format($running['km'], 1), '0'), '.') }}<span>km</span></dd></div>
            @if ($pace = \App\Services\Strava::formatPace($running['pace_seconds_per_km']))
                <div><dt>Average pace</dt><dd>{{ $pace }}<span>/km</span></dd></div>
            @endif
            <div><dt>Runs</dt><dd>{{ $running['runs'] }}</dd></div>
        </dl>
        <p class="run-card__source">
            Retrieved automatically from the Strava API, updated hourly
            (last at <time datetime="{{ $running['fetched_at'] }}">{{ \Illuminate\Support\Carbon::parse($running['fetched_at'])->format('H:i, j M') }}</time>).
            Public activities only.
        </p>
    </article>
</section>
@endif

@if ($grafana = config('services.grafana.url'))
    @php
        $dashboard = rtrim($grafana, '/').config('services.grafana.dashboard');
        $traceId = request()->attributes->get('trace_id');
    @endphp
    <section class="section section--tight extras" id="observability" aria-labelledby="observability-h">
        <h2 id="observability-h" class="tape tape--section">Observability</h2>
        <article class="run-card obs-card">
            <h3 class="run-card__title obs-card__title">Live telemetry in Grafana</h3>
            <p class="obs-card__lead">
                This site is instrumented with OpenTelemetry. Every page view is traced and logged into the LGTM stack:
                Loki for logs, Grafana for dashboards, Tempo for traces and Mimir for metrics, collected by Grafana Alloy.
            </p>
            <p class="obs-card__actions">
                <a class="btn btn--signal btn--lg" href="{{ $dashboard }}" rel="noopener">Open the live dashboard</a>
                @if ($traceId)
                    <a class="btn btn--line btn--lg" href="{{ $dashboard }}?var-trace_id={{ $traceId }}" rel="noopener">See this page view</a>
                @endif
            </p>
            <p class="run-card__source">
                Each page view is recorded with its method, path, status, timing and browser user agent. IP addresses are anonymised
                before anything is stored (the last block of an IPv4 address becomes 0; IPv6 is cut to its first 48 bits), nothing typed
                into the contact form is recorded, and data is kept for 7 days.
            </p>
        </article>
    </section>
@endif

<section class="section section--tight status" id="status" aria-labelledby="status-h">
    <h2 id="status-h" class="tape tape--section">Site status</h2>
    @if (! $latest)
        <p class="empty">No checks recorded yet. They start once the scheduler runs (<code>php artisan schedule:run</code> every minute).</p>
    @else
        <p @class(['status__now', 'is-down' => ! $latest->ok])>
            <span class="status__dot" aria-hidden="true"></span>
            {{ $latest->ok ? 'Up' : 'Down' }}
            <span class="status__when">checked {{ $latest->checked_at->diffForHumans() }}@if ($latest->ok && $latest->response_ms), {{ $latest->response_ms }} ms @endif</span>
        </p>

        <dl class="status__figures">
            @foreach ($uptime as $label => $pct)
                <div><dt>Uptime, {{ $label }}</dt><dd>{{ $pct === null ? '—' : rtrim(rtrim(number_format($pct, 2), '0'), '.').'%' }}</dd></div>
            @endforeach
            <div><dt>Average response, 24 hours</dt><dd>{{ $avgMs === null ? '—' : $avgMs.' ms' }}</dd></div>
            <div><dt>95th percentile, 24 hours</dt><dd>{{ $p95Ms === null ? '—' : $p95Ms.' ms' }}</dd></div>
        </dl>

        <figure class="status__strip">
            <figcaption>Last 48 hours, one block per hour</figcaption>
            <ol>
                @foreach ($hours as $h)
                    @php($state = $h['uptime'] === null ? 'none' : ($h['uptime'] >= 1 ? 'up' : ($h['uptime'] >= 0.9 ? 'degraded' : 'down')))
                    <li class="is-{{ $state }}" title="{{ $h['at']->format('D H:00') }}: {{ $h['uptime'] === null ? 'no data' : round($h['uptime'] * 100).'% up' }}">
                        <span class="visually-hidden">{{ $h['at']->format('D H:00') }}: {{ $h['uptime'] === null ? 'no data' : round($h['uptime'] * 100).'% up' }}</span>
                    </li>
                @endforeach
            </ol>
            <p class="status__legend" aria-hidden="true">
                <span class="is-up"></span> up <span class="is-degraded"></span> some failures <span class="is-down"></span> mostly down <span class="is-none"></span> no data
            </p>
        </figure>
    @endif

    <h2 class="tape tape--section status__deploys-h">Deploys</h2>
    @if ($deploys->isEmpty())
        <p class="empty">No deploys recorded yet. The pipeline records one with <code>php artisan deploy:record</code>.</p>
    @else
        <ol class="ledger status__deploys">
            @foreach ($deploys as $d)
                <li class="ledger__row">
                    <div class="status__deploy">
                        <code class="status__sha">{{ $d->shortCommit() }}</code>
                        <span class="status__msg">{{ $d->message ?? 'No commit message' }}</span>
                        <span class="status__meta">
                            <time datetime="{{ $d->deployed_at->toIso8601String() }}">{{ $d->deployed_at->format('j M Y, H:i') }}</time>
                            @if ($d->duration()) · {{ $d->duration() }} @endif
                            @if ($d->run_url) · <a href="{{ $d->run_url }}" rel="noopener">run</a>@endif
                        </span>
                    </div>
                </li>
            @endforeach
        </ol>
    @endif
</section>
@endsection
