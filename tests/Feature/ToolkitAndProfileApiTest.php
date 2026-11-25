<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Profile;
use App\Models\SkillGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ToolkitAndProfileApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guests_can_read_but_not_write(): void
    {
        $this->getJson('/api/skill-groups')->assertOk()->assertJsonPath('data.0.name', 'Security');
        $this->getJson('/api/education')->assertOk();
        $this->getJson('/api/profile')->assertOk()->assertJsonPath('data.name', 'Your Name');

        $this->postJson('/api/skill-groups', ['name' => 'X', 'skills' => 'Y'])->assertUnauthorized();
        $this->putJson('/api/profile', ['name' => 'Someone Else'])->assertUnauthorized();
        foreach (['/toolkit/create', '/profile/edit', '/education/create'] as $page) {
            $this->get($page)->assertRedirect('/login');
        }
    }

    public function test_toolkit_groups_can_be_added_edited_and_deleted(): void
    {
        $this->login();
        $this->get('/toolkit/create')->assertOk()->assertSee('New skill group');

        $id = $this->postJson('/api/skill-groups', ['name' => 'Languages', 'skills' => "Go\nRust, Python, Go"])
            ->assertCreated()->assertJsonPath('data.skills', ['Go', 'Rust', 'Python'])->assertJsonPath('data.sort_order', 3)->json('data.id');

        $this->get('/')->assertSee('Languages')->assertSee('Add group');
        $this->patchJson("/api/skill-groups/{$id}", ['skills' => ['Go', 'Zig']])->assertOk()->assertJsonPath('data.skills', ['Go', 'Zig']);
        $this->get("/toolkit/{$id}/edit")->assertOk()->assertSee('Go, Zig');

        $this->deleteJson("/api/skill-groups/{$id}")->assertNoContent();
        $this->assertSame(3, SkillGroup::count());
    }

    public function test_education_can_be_added_edited_and_deleted(): void
    {
        $this->login();
        $this->get('/education/create')->assertOk()->assertSee('New qualification');

        $id = $this->postJson('/api/education', ['qualification' => 'CKA', 'institution' => 'CNCF', 'sort_order' => ''])
            ->assertCreated()->assertJsonPath('data.sort_order', 4)->json('data.id');
        $this->get("/education/{$id}/edit")->assertOk()->assertSee('CNCF');

        $featured = Education::where('featured', true)->first();
        $this->putJson("/api/education/{$featured->id}", ['featured' => '0'])->assertOk()->assertJsonPath('data.featured', false);

        $this->deleteJson("/api/education/{$id}")->assertNoContent();
        $this->assertNull(Education::find($id));
    }

    public function test_profile_updates_show_across_the_site(): void
    {
        $this->login();
        $this->get('/profile/edit')->assertOk()->assertSee('Edit profile');

        $this->putJson('/api/profile', ['headline' => 'Platform Engineer', 'github_url' => 'not a url'])->assertJsonValidationErrors('github_url');
        $this->putJson('/api/profile', ['headline' => 'Platform Engineer'])->assertOk()->assertJsonPath('data.name', 'Your Name');

        $this->get('/')->assertSee('Platform Engineer');
        $this->get('/cv')->assertSee('Platform Engineer');
    }

    public function test_spaniel_can_be_switched_off(): void
    {
        $this->get('/')->assertSee('class="spaniel"', false);

        $this->login();
        $this->putJson('/api/profile', ['show_mascot' => '0'])->assertOk()->assertJsonPath('data.show_mascot', false);
        $this->get('/')->assertDontSee('class="spaniel"', false);

        $this->putJson('/api/profile', ['show_mascot' => true])->assertJsonPath('data.show_mascot', true);
        $this->get('/')->assertSee('class="spaniel"', false);
    }

    public function test_cv_is_only_the_generated_page(): void
    {
        $this->get('/cv')->assertOk()->assertSee('Save as PDF')->assertDontSee('Original PDF');
    }

    public function test_first_save_creates_the_profile_on_an_unseeded_site(): void
    {
        Profile::query()->delete();
        $this->login();

        $this->putJson('/api/profile', ['name' => 'Ada'])->assertJsonValidationErrors(['headline', 'email', 'intro']);
        $this->putJson('/api/profile', ['name' => 'Ada', 'headline' => 'Engineer', 'email' => 'ada@example.com', 'intro' => 'Hello.'])->assertOk();
        $this->assertSame('Ada', Profile::first()->name);
    }

    private function login(): void
    {
        $this->actingAs(User::create(['name' => 'admin', 'password' => bcrypt('x')]));
    }
}
