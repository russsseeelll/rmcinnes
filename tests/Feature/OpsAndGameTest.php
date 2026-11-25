<?php

namespace Tests\Feature;

use App\Models\Deploy;
use App\Models\HealthCheck;
use App\Models\Score;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpsAndGameTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_recorded_deploy_shows_in_footer_and_status(): void
    {
        $this->artisan('deploy:record', ['--commit' => str_repeat('a1', 20), '--message' => 'Ship it', '--duration' => 134])
            ->assertSuccessful();

        $this->get('/')->assertSee('a1a1a1a')->assertSee('2m 14s');
        $this->get('/extras')->assertOk()->assertSee('Ship it');
        $this->get('/status')->assertRedirect('/extras#status');
    }

    public function test_deploy_record_rejects_a_bad_sha(): void
    {
        $this->artisan('deploy:record', ['--commit' => 'not-a-sha'])->assertFailed();
        $this->assertSame(0, Deploy::count());
    }

    public function test_status_page_reports_uptime(): void
    {
        $this->get('/extras')->assertOk()->assertSee('No checks recorded yet');

        HealthCheck::create(['ok' => true, 'status_code' => 200, 'response_ms' => 80, 'checked_at' => now()->subMinutes(10)]);
        HealthCheck::create(['ok' => false, 'status_code' => 500, 'response_ms' => 90, 'checked_at' => now()->subMinutes(5)]);

        $this->get('/extras')->assertOk()->assertSee('Down')->assertSee('50%');
    }

    public function test_cv_page_renders_from_the_database(): void
    {
        $this->get('/cv')->assertOk()->assertSee('Your Name')->assertSee('Senior Linux System Administrator')->assertSee('Save as PDF');
    }

    public function test_leaderboard_is_seeded_and_capped_at_ten(): void
    {
        $this->getJson('/scores')->assertOk()->assertJsonCount(10)->assertJsonPath('0.name', 'Jared Isaacman');
    }

    public function test_leaderboard_takes_no_submissions(): void
    {
        $this->postJson('/scores', ['name' => 'Someone', 'score' => 5])->assertStatus(405);
    }

    public function test_leaderboard_entries_are_managed_when_logged_in(): void
    {
        $this->postJson('/api/scores', ['name' => 'X', 'score' => 1])->assertUnauthorized();
        $this->get('/leaderboard')->assertRedirect('/login');

        $this->actingAs(\App\Models\User::create(['name' => 'admin', 'password' => bcrypt('x')]));
        $this->get('/leaderboard')->assertOk()->assertSee('Jared Isaacman');

        $id = $this->postJson('/api/scores', ['name' => 'Ada Lovelace', 'organisation' => 'Analytical Engines', 'score' => 99])
            ->assertCreated()->json('data.id');
        $this->getJson('/scores')->assertJsonPath('0.name', 'Ada Lovelace');

        $this->putJson("/api/scores/{$id}", ['score' => 1000])->assertJsonValidationErrors('score');
        $this->putJson("/api/scores/{$id}", ['score' => 5])->assertOk()->assertJsonPath('data.score', 5);

        $this->deleteJson("/api/scores/{$id}")->assertNoContent();
        $this->assertNull(\App\Models\Score::find($id));
    }

    public function test_extras_links_to_grafana_only_when_configured(): void
    {
        $this->get('/extras')->assertDontSee('id="observability"', false);

        config(['services.grafana.url' => 'https://grafana.example.test']);
        $this->get('/extras')->assertSee('id="observability"', false)
            ->assertSee('https://grafana.example.test/d/rmcinnes-live/rmcinnes-co-uk-live', false)
            ->assertSee('IP addresses are anonymised');
    }
}
