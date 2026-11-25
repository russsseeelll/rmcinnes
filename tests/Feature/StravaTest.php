<?php

namespace Tests\Feature;

use App\Models\StravaConnection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StravaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Cache::flush();
        config(['services.strava' => [
            'client_id' => '123', 'client_secret' => 'secret', 'refresh_token' => 'refresh-from-env', 'access_token' => null,
        ]]);
    }

    public function test_sync_refreshes_the_token_and_shows_running_stats(): void
    {
        Http::fake([
            'www.strava.com/oauth/token' => Http::response(['access_token' => 'access-1', 'refresh_token' => 'refresh-rotated', 'expires_at' => now()->addHours(6)->timestamp]),
            'www.strava.com/api/v3/athlete' => Http::response(['id' => 42]),
            'www.strava.com/api/v3/athletes/42/stats' => Http::response(['ytd_run_totals' => ['count' => 22, 'distance' => 139512.3, 'moving_time' => 41020]]),
        ]);

        $this->artisan('strava:sync')->expectsOutputToContain('139.5 km over 22 runs')->assertSuccessful();

        $this->get('/')->assertDontSee('139.5');
        $this->get('/extras')->assertSee('Running')->assertSee('139.5')->assertSee('4:54')->assertSee('Strava API');
        $this->assertSame('refresh-rotated', Cache::get('strava:refresh_token'));
        Http::assertSent(fn ($r) => str_contains($r->url(), 'oauth/token') && $r['refresh_token'] === 'refresh-from-env');
    }

    public function test_failed_sync_keeps_the_last_good_numbers(): void
    {
        Cache::forever('strava:running', ['year' => now()->year, 'km' => 100.0, 'runs' => 10, 'pace_seconds_per_km' => 300, 'fetched_at' => now()->toIso8601String()]);
        Http::fake(['*' => Http::response('down', 500)]);

        $this->artisan('strava:sync')->assertFailed();
        $this->get('/extras')->assertSee('5:00');
    }

    public function test_extras_are_hidden_without_strava(): void
    {
        config(['services.strava' => ['client_id' => null, 'client_secret' => null, 'refresh_token' => null, 'access_token' => null]]);
        Http::fake();

        $this->artisan('strava:sync')->expectsOutputToContain('not configured')->assertSuccessful();
        $this->get('/extras')->assertOk()->assertDontSee('id="running"', false);
        Http::assertNothingSent();
    }

    public function test_database_credentials_are_used_when_env_is_empty(): void
    {
        config(['services.strava' => ['client_id' => null, 'client_secret' => null, 'refresh_token' => null, 'access_token' => null]]);
        $this->actingAs(User::create(['name' => 'admin', 'password' => bcrypt('x')]));

        $this->get('/strava')->assertOk()->assertSee('not set');
        $this->putJson('/api/strava', ['client_id' => '999', 'client_secret' => 'db-secret', 'refresh_token' => 'db-refresh'])
            ->assertOk()
            ->assertJsonPath('data.configured', true)
            ->assertJsonPath('data.fields.client_secret.source', 'database')
            ->assertJsonMissingPath('data.fields.client_secret.value');

        $raw = DB::table('strava_connections')->first();
        $this->assertNotSame('db-secret', $raw->client_secret);
        $this->assertSame('db-secret', StravaConnection::current()->client_secret);

        $this->putJson('/api/strava', ['client_id' => '999', 'client_secret' => '', 'refresh_token' => ''])->assertOk();
        $this->assertSame('db-refresh', StravaConnection::current()->refresh_token);

        Http::fake([
            'www.strava.com/oauth/token' => Http::response(['access_token' => 'a', 'refresh_token' => 'db-refresh-2', 'expires_at' => now()->addHours(6)->timestamp]),
            'www.strava.com/api/v3/athlete' => Http::response(['id' => 7]),
            'www.strava.com/api/v3/athletes/7/stats' => Http::response(['ytd_run_totals' => ['count' => 3, 'distance' => 15000, 'moving_time' => 4500]]),
        ]);
        $this->postJson('/api/strava/sync')->assertOk()->assertJsonPath('data.running.km', 15);
        Http::assertSent(fn ($r) => str_contains($r->url(), 'oauth/token') && $r['client_secret'] === 'db-secret');
        $this->assertSame('db-refresh-2', StravaConnection::current()->refresh_token);

        $this->deleteJson('/api/strava')->assertNoContent();
        $this->assertSame(0, StravaConnection::count());
        $this->get('/extras')->assertOk()->assertDontSee('id="running"', false);
    }

    public function test_env_values_take_priority_over_the_database(): void
    {
        StravaConnection::create(['client_id' => 'db-id', 'client_secret' => 'db-secret', 'refresh_token' => 'db-refresh']);
        $this->actingAs(User::create(['name' => 'admin', 'password' => bcrypt('x')]));

        $this->getJson('/api/strava')->assertJsonPath('data.fields.client_id.source', 'env')->assertJsonPath('data.fields.client_id.value', '123');
        $this->get('/strava')->assertSee('set in .env');
    }

    public function test_sync_errors_are_reported_and_settings_need_login(): void
    {
        $this->getJson('/api/strava')->assertUnauthorized();
        $this->get('/strava')->assertRedirect('/login');

        $this->actingAs(User::create(['name' => 'admin', 'password' => bcrypt('x')]));
        Http::fake(['*' => Http::response(['message' => 'Authorization Error'], 401)]);
        $this->postJson('/api/strava/sync')->assertStatus(502)->assertJsonPath('message', fn ($m) => str_starts_with($m, "Strava didn't accept those details"));
    }
}
