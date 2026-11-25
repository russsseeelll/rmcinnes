<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    private array $uploaded = [];

    protected function tearDown(): void
    {
        foreach ($this->uploaded as $path) {
            File::delete(public_path($path));
        }
        parent::tearDown();
    }

    public function test_login_with_name_and_password_lands_on_projects(): void
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        User::create(['name' => 'admin', 'password' => bcrypt('secret-pass')]);

        $this->post('/login', ['name' => 'admin', 'password' => 'wrong'])->assertSessionHasErrors('name');
        $this->post('/login', ['name' => 'admin', 'password' => 'secret-pass'])->assertRedirect(route('portfolio'));
        $this->assertAuthenticated();
    }

    public function test_admin_pages_need_login_and_render_the_api_form(): void
    {
        $this->get('/portfolio/create')->assertRedirect('/login');

        $this->actingAs($this->admin());
        $this->get('/portfolio/create')->assertOk()->assertSee('New project')->assertSee('data-api-form', false);

        $project = Portfolio::create($this->valid());
        $this->get("/portfolio/{$project->slug}/edit")->assertOk()->assertSee('Delete project');
    }

    public function test_reads_are_public(): void
    {
        Portfolio::create($this->valid());

        $this->getJson('/api/projects')->assertOk()->assertJsonPath('data.0.slug', 'homelab')->assertJsonPath('data.0.tags', ['Kubernetes', 'Proxmox']);
        $this->getJson('/api/projects/homelab')->assertOk()->assertJsonPath('data.url', route('portfolio.single', 'homelab'));
        $this->getJson('/api/projects?category=Nope')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_writes_need_auth(): void
    {
        $this->postJson('/api/projects', $this->valid())->assertUnauthorized();
        $this->assertSame(0, Portfolio::count());
    }

    public function test_session_user_creates_with_image(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/api/projects', $this->valid(['image' => $this->png()]), ['Accept' => 'application/json']);
        $project = Portfolio::firstOrFail();
        $this->uploaded[] = $project->image;

        $response->assertCreated()->assertJsonPath('data.url', route('portfolio.single', 'homelab'));
        $this->assertFileExists(public_path($project->image));
        $this->get('/portfolio/homelab?done=added')->assertOk()->assertSee('Project added.')->assertSee('Edit project');
    }

    public function test_token_with_write_ability_can_manage_projects(): void
    {
        $token = $this->admin()->createToken('claude', ['projects:write'])->plainTextToken;
        $auth = ['Authorization' => "Bearer $token"];

        $this->postJson('/api/projects', $this->valid(['tags' => ['Go', 'Kubernetes'], 'link' => null]), $auth)
            ->assertCreated()->assertJsonPath('data.tags', ['Go', 'Kubernetes']);

        $this->patchJson('/api/projects/homelab', ['summary' => 'Updated by Claude.'], $auth)
            ->assertOk()->assertJsonPath('data.summary', 'Updated by Claude.')->assertJsonPath('data.title', 'Homelab');

        $this->deleteJson('/api/projects/homelab', [], $auth)->assertNoContent();
        $this->assertSame(0, Portfolio::count());
    }

    public function test_token_without_write_ability_is_refused(): void
    {
        $token = $this->admin()->createToken('read-only', [])->plainTextToken;

        $this->postJson('/api/projects', $this->valid(), ['Authorization' => "Bearer $token"])->assertForbidden();
    }

    public function test_validation_errors_come_back_per_field(): void
    {
        $this->actingAs($this->admin());

        $this->postJson('/api/projects', $this->valid(['title' => '', 'link' => 'not a url']))
            ->assertUnprocessable()->assertJsonValidationErrors(['title', 'link']);
    }

    public function test_optional_fields_can_be_left_empty(): void
    {
        $this->actingAs($this->admin());

        $this->postJson('/api/projects', $this->valid(['tags' => '', 'link' => '']))->assertCreated();
        $this->get('/portfolio/homelab')->assertOk();
        $this->get('/portfolio')->assertOk()->assertSee('Homelab');
    }

    public function test_titles_that_slug_the_same_do_not_crash(): void
    {
        $this->actingAs($this->admin());

        $this->postJson('/api/projects', $this->valid(['title' => 'My App']))->assertCreated();
        $this->postJson('/api/projects', $this->valid(['title' => 'My-App']))->assertCreated();

        $slugs = Portfolio::pluck('slug');
        $this->assertCount(2, $slugs->unique());
        $this->assertContains('my-app', $slugs);
    }

    public function test_edit_renames_replaces_and_removes_image(): void
    {
        $this->actingAs($this->admin());
        $headers = ['Accept' => 'application/json'];
        $this->post('/api/projects', $this->valid(['image' => $this->png()]), $headers)->assertCreated();
        $project = Portfolio::firstOrFail();
        $first = $project->image;

        $this->post('/api/projects/homelab', $this->valid(['_method' => 'PUT', 'title' => 'Home lab v2', 'image' => $this->png()]), $headers)
            ->assertOk()->assertJsonPath('data.slug', 'home-lab-v2');
        $project->refresh();
        $this->uploaded[] = $project->image;
        $this->assertFileDoesNotExist(public_path($first));
        $this->assertFileExists(public_path($project->image));

        $this->putJson('/api/projects/home-lab-v2', ['summary' => 'Still has its image.'])->assertOk();
        $this->assertSame($project->image, $project->fresh()->image);

        $this->putJson('/api/projects/home-lab-v2', ['remove_image' => true])->assertOk()->assertJsonPath('data.image_url', null);
        $this->assertFileDoesNotExist(public_path($project->image));
    }

    public function test_renaming_to_an_existing_title_is_rejected(): void
    {
        $this->actingAs($this->admin());
        $this->postJson('/api/projects', $this->valid(['title' => 'One']));
        $this->postJson('/api/projects', $this->valid(['title' => 'Two']));

        $this->putJson('/api/projects/two', ['title' => 'One'])->assertJsonValidationErrors('title');
        $this->putJson('/api/projects/two', ['title' => 'Two', 'summary' => 'Changed'])->assertOk();
    }

    public function test_delete_removes_project_and_image(): void
    {
        $this->actingAs($this->admin());
        $this->post('/api/projects', $this->valid(['image' => $this->png()]), ['Accept' => 'application/json']);
        $project = Portfolio::firstOrFail();

        $this->deleteJson('/api/projects/homelab')->assertNoContent();
        $this->assertSame(0, Portfolio::count());
        $this->assertFileDoesNotExist(public_path($project->image));
        $this->get('/portfolio?deleted=Homelab')->assertSee('“Homelab” deleted.');

        $this->postJson('/api/projects', $this->valid());
        $this->deleteJson('/api/projects/homelab')->assertNoContent();
        $this->assertDirectoryExists(public_path());
    }

    public function test_api_token_command_issues_a_write_token(): void
    {
        $this->admin();

        $this->artisan('api:token', ['--name' => 'claude'])->assertSuccessful();
        $this->assertTrue(User::first()->tokens()->first()->can('projects:write'));
    }

    private function admin(): User
    {
        return User::create(['name' => 'admin', 'password' => bcrypt('x')]);
    }

    private function valid(array $overrides = []): array
    {
        return $overrides + [
            'title' => 'Homelab',
            'date' => '2026-01-01',
            'category' => 'Platform',
            'tags' => 'Kubernetes, Proxmox',
            'summary' => 'A summary.',
            'description' => 'A description.',
            'link' => 'https://github.com/example/homelab',
        ];
    }

    private function png(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));

        return new UploadedFile($path, 'shot.png', 'image/png', null, true);
    }
}
