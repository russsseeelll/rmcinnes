# {!! $profile->name !!}

> {!! $profile->headline !!}{!! $profile->location ? ', based in '.$profile->location : '' !!}. {!! $profile->intro !!}

@if ($profile->ai_summary)
{!! $profile->ai_summary !!}

@endif
## Links

- [Website]({{ url('/') }}): portfolio, experience, skills and projects
- [CV]({{ route('cv') }}): printable CV generated from the same data as this file
- [Projects]({{ route('portfolio') }}): all projects with write-ups
@if (config('services.grafana.url'))
- [Live observability dashboard]({{ rtrim(config('services.grafana.url'), '/').config('services.grafana.dashboard') }}): this site's own telemetry in Grafana (OpenTelemetry, Loki, Tempo, Mimir)
@endif
@if ($profile->github_url)
- [GitHub]({!! $profile->github_url !!})
@endif
@if ($profile->linkedin_url)
- [LinkedIn]({!! $profile->linkedin_url !!})
@endif
- Email: {!! $profile->email !!}

## Experience

@foreach ($experiences as $job)
### {!! $job->title !!}, {!! $job->organisation !!} ({{ $job->period() }})

@foreach ($job->highlightList() as $line)
- {!! $line !!}
@endforeach

@endforeach
## Skills

@foreach ($skillGroups as $group)
- {!! $group->name !!}: {!! implode(', ', $group->skillList()) !!}
@endforeach

## Education and certifications

@foreach ($education as $item)
- {!! collect([$item->qualification, $item->institution, $item->grade, $item->period])->filter()->implode(', ') !!}
@endforeach

## Projects

@foreach ($projects as $p)
### [{!! $p->title !!}]({{ route('portfolio.single', $p->slug) }})

{!! $p->summary !!}

- Category: {!! $p->category !!}
- Date: {{ \Illuminate\Support\Carbon::parse($p->date)->format('F Y') }}
@if ($p->tagList())
- Technologies: {!! implode(', ', $p->tagList()) !!}
@endif
@if ($p->link)
- Link: {!! $p->link !!}
@endif

@endforeach
