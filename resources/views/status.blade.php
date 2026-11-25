@extends('layouts.app', ['title' => 'Status · '.$profile->name])

@section('content')
<section class="page-head">
    <h1 class="tape tape--hero">Status</h1>
    <p class="page-head__sub">
        How this site is running. The server checks its own homepage every five minutes; deploys are recorded by the pipeline.
    </p>
</section>

<section class="section section--tight status">
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
