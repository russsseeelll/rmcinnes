<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StravaConnection;
use App\Services\Strava;
use Illuminate\Http\Request;
use Throwable;

class StravaController extends Controller
{
    public function __construct(private Strava $strava) {}

    public function show()
    {
        return ['data' => $this->present()];
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'client_id' => ['nullable', 'string', 'max:50'],
            'client_secret' => ['nullable', 'string', 'max:255'],
            'refresh_token' => ['nullable', 'string', 'max:255'],
        ]);

        $connection = StravaConnection::current();
        $connection->fill(array_filter($fields, 'filled'))->save();
        if (filled($fields['refresh_token'] ?? null) || filled($fields['client_id'] ?? null)) {
            $this->strava->forget(stats: false);
        }

        return ['data' => $this->present()];
    }

    public function destroy()
    {
        StravaConnection::query()->delete();
        $this->strava->forget();

        return response()->noContent();
    }

    public function sync()
    {
        if (! $this->strava->configured()) {
            return response()->json(['message' => 'Add a client ID, client secret and refresh token first.'], 422);
        }

        try {
            $this->strava->sync();
        } catch (Throwable $e) {
            report($e);

            return response()->json(['message' => "Strava didn't accept those details: ".$e->getMessage()], 502);
        }

        return ['data' => $this->present()];
    }

    private function present(): array
    {
        $fields = [];
        foreach (['client_id', 'client_secret', 'refresh_token'] as $key) {
            $fields[$key] = ['set' => filled($this->strava->credential($key)), 'source' => $this->strava->source($key)];
        }
        $fields['client_id']['value'] = $this->strava->credential('client_id');

        return [
            'fields' => $fields,
            'configured' => $this->strava->configured(),
            'running' => $this->strava->running(),
            'url' => route('strava.edit'),
        ];
    }
}
