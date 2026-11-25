<?php

namespace App\Services;

use App\Models\StravaConnection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class Strava
{
    private const RUNNING = 'strava:running';
    private const REFRESH_TOKEN = 'strava:refresh_token';
    private const ACCESS_TOKEN = 'strava:access_token';
    private const ATHLETE_ID = 'strava:athlete_id';

    public function source(string $key): ?string
    {
        if (filled(config("services.strava.$key"))) {
            return 'env';
        }

        return in_array($key, ['client_id', 'client_secret', 'refresh_token']) && filled(StravaConnection::current()->$key) ? 'database' : null;
    }

    public function credential(string $key): ?string
    {
        return match ($this->source($key)) {
            'env' => config("services.strava.$key"),
            'database' => StravaConnection::current()->$key,
            default => null,
        };
    }

    public function configured(): bool
    {
        return filled($this->credential('client_id')) && filled($this->credential('client_secret'))
            && (filled($this->credential('refresh_token')) || filled($this->credential('access_token')));
    }

    public function running(): ?array
    {
        $stats = Cache::get(self::RUNNING);

        return $stats && $stats['year'] === now()->year ? $stats : null;
    }

    public function forget(bool $stats = true): void
    {
        Cache::forget(self::ACCESS_TOKEN);
        Cache::forget(self::REFRESH_TOKEN);
        Cache::forget(self::ATHLETE_ID);
        if ($stats) {
            Cache::forget(self::RUNNING);
        }
    }

    public function sync(): array
    {
        $token = $this->accessToken();
        $athleteId = Cache::rememberForever(self::ATHLETE_ID, fn () => $this->get($token, 'athlete')['id']);
        $totals = $this->get($token, "athletes/{$athleteId}/stats")['ytd_run_totals'] ?? [];

        $metres = (float) ($totals['distance'] ?? 0);
        $seconds = (int) ($totals['moving_time'] ?? 0);
        $stats = [
            'year' => now()->year,
            'km' => round($metres / 1000, 1),
            'runs' => (int) ($totals['count'] ?? 0),
            'pace_seconds_per_km' => $metres > 0 ? (int) round($seconds / ($metres / 1000)) : null,
            'fetched_at' => now()->toIso8601String(),
        ];
        Cache::forever(self::RUNNING, $stats);

        return $stats;
    }

    public static function formatPace(?int $secondsPerKm): ?string
    {
        return $secondsPerKm ? intdiv($secondsPerKm, 60).':'.str_pad($secondsPerKm % 60, 2, '0', STR_PAD_LEFT) : null;
    }

    private function get(string $token, string $path): array
    {
        return Http::withToken($token)->acceptJson()->timeout(15)
            ->get("https://www.strava.com/api/v3/{$path}")->throw()->json();
    }

    private function accessToken(): string
    {
        if ($cached = Cache::get(self::ACCESS_TOKEN)) {
            return $cached;
        }

        $refresh = Cache::get(self::REFRESH_TOKEN) ?? $this->credential('refresh_token');
        if (! $refresh) {
            return $this->credential('access_token') ?? throw new RuntimeException('No Strava token configured.');
        }

        $response = Http::asForm()->timeout(15)->post('https://www.strava.com/oauth/token', [
            'client_id' => $this->credential('client_id'),
            'client_secret' => $this->credential('client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh,
        ])->throw()->json();

        Cache::forever(self::REFRESH_TOKEN, $response['refresh_token']);
        if ($this->source('refresh_token') === 'database') {
            StravaConnection::current()->update(['refresh_token' => $response['refresh_token']]);
        }
        Cache::put(self::ACCESS_TOKEN, $response['access_token'], now()->setTimestamp($response['expires_at'])->subMinutes(5));

        return $response['access_token'];
    }
}
