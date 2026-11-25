<?php

namespace App\Http\Controllers;

use App\Models\Deploy;
use App\Models\HealthCheck;
use App\Services\Strava;

class StatusController extends Controller
{
    public function __invoke(Strava $strava)
    {
        $uptime = fn (int $days) => $this->uptime(HealthCheck::where('checked_at', '>=', now()->subDays($days)));

        $day = HealthCheck::where('checked_at', '>=', now()->subDay())->orderBy('checked_at')->get();
        $times = $day->where('ok', true)->pluck('response_ms')->sort()->values();

        $since = now()->subHours(47)->startOfHour();
        $byHour = HealthCheck::where('checked_at', '>=', $since)->get()
            ->groupBy(fn ($c) => $c->checked_at->format('Y-m-d H'));
        $hours = collect(range(0, 47))->map(function ($i) use ($since, $byHour) {
            $hour = $since->copy()->addHours($i);
            $checks = $byHour->get($hour->format('Y-m-d H'));

            return ['at' => $hour, 'uptime' => $checks ? $checks->where('ok', true)->count() / $checks->count() : null];
        });

        return view('extras', [
            'running' => $strava->running(),
            'latest' => HealthCheck::latest('checked_at')->first(),
            'uptime' => ['24 hours' => $uptime(1), '7 days' => $uptime(7), '30 days' => $uptime(30)],
            'avgMs' => $times->isEmpty() ? null : (int) round($times->avg()),
            'p95Ms' => $times->isEmpty() ? null : $times[(int) floor(0.95 * ($times->count() - 1))],
            'hours' => $hours,
            'deploys' => Deploy::latest('deployed_at')->limit(10)->get(),
        ]);
    }

    private function uptime($query): ?float
    {
        $total = (clone $query)->count();

        return $total ? round(100 * $query->where('ok', true)->count() / $total, 2) : null;
    }
}
