<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperienceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_reads_are_public_and_writes_need_auth(): void
    {
        $this->seed();

        $this->getJson('/api/experience')->assertOk()->assertJsonPath('data.0.title', 'Senior Linux System Administrator');
        $this->postJson('/api/experience', $this->valid())->assertUnauthorized();
        $this->get('/experience/create')->assertRedirect('/login');
    }

    public function test_logged_in_user_adds_edits_and_deletes_a_role(): void
    {
        $this->actingAs(User::create(['name' => 'admin', 'password' => bcrypt('x')]));

        $this->get('/experience/create')->assertOk()->assertSee('New role');

        $id = $this->postJson('/api/experience', $this->valid(['highlights' => "- Built the thing\n\n• Ran the other thing "]))
            ->assertCreated()
            ->assertJsonPath('data.highlights', ['Built the thing', 'Ran the other thing'])
            ->assertJsonPath('data.period', 'Mar 2026 – now')
            ->json('data.id');

        $this->get("/experience/{$id}/edit")->assertOk()->assertSee('Built the thing');
        $this->get('/')->assertSee('Platform Engineer')->assertSee('Add role');

        $this->patchJson("/api/experience/{$id}", ['ended_on' => '2026-10-01', 'highlights' => ['One', 'Two']])
            ->assertOk()->assertJsonPath('data.period', 'Mar 2026 – Oct 2026')->assertJsonPath('data.highlights', ['One', 'Two']);

        $this->patchJson("/api/experience/{$id}", ['ended_on' => '2020-01-01'])->assertJsonValidationErrors('ended_on');

        $this->deleteJson("/api/experience/{$id}")->assertNoContent();
        $this->assertSame(0, Experience::count());
    }

    public function test_token_can_add_a_role(): void
    {
        $token = User::create(['name' => 'admin', 'password' => bcrypt('x')])->createToken('claude', ['projects:write'])->plainTextToken;

        $this->postJson('/api/experience', $this->valid(), ['Authorization' => "Bearer $token"])->assertCreated();
    }

    private function valid(array $overrides = []): array
    {
        return $overrides + [
            'title' => 'Platform Engineer',
            'organisation' => 'Example Ltd',
            'started_on' => '2026-03-01',
            'ended_on' => null,
            'highlights' => 'Built the thing',
        ];
    }
}
