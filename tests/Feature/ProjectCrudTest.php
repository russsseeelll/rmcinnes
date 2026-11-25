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

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    protected function tearDown(): void
    {
        foreach ($this->uploaded as $path) {
            File::delete(public_path($path));
        }
        parent::tearDown();
    }

    public function test_login_with_name_and_password_lands_on_projects(): void
    {
        User::create(['name' => 'admin', 'password' => bcrypt('secret-pass')]);

        $this->post('/login', ['name' => 'admin', 'password' => 'wrong'])->assertSessionHasErrors('name');
        $this->post('/login', ['name' => 'admin', 'password' => 'secret-pass'])->assertRedirect(route('portfolio'));
        $this->assertAuthenticated();
    }

    public function test_guests_cannot_reach_admin_pages(): void
    {
        $this->get('/portfolio/create')->assertRedirect('/login');
        $this->post('/portfolio', $this->valid())->assertRedirect('/login');
        $this->assertSame(0, Portfolio::count());
    }

    public function test_create_with_image_then_view(): void
    {
        $this->actingAs($this->admin());

        $this->get('/portfolio/create')->assertOk()->assertSee('New project');

        $response = $this->post('/portfolio', $this->valid(['image' => $this->png()]));
        $project = Portfolio::firstOrFail();
        $this->uploaded[] = $project->image;

        $response->assertRedirect(route('portfolio.single', 'homelab'))->assertSessionHas('message', 'Project added.');
        $this->assertFileExists(public_path($project->image));
        $this->get('/portfolio/homelab')->assertOk()->assertSee('Project added.')->assertSee('Edit project');
    }

    public function test_validation_errors_are_shown_and_input_kept(): void
    {
        $this->actingAs($this->admin());

        $this->from('/portfolio/create')
            ->post('/portfolio', $this->valid(['title' => '', 'link' => 'not a url']))
            ->assertRedirect('/portfolio/create')
            ->assertSessionHasErrors(['title', 'link']);

        $this->get('/portfolio/create')->assertSee('Some fields need fixing');
    }

    public function test_titles_that_slug_the_same_do_not_crash(): void
    {
        $this->actingAs($this->admin());

        $this->post('/portfolio', $this->valid(['title' => 'My App']));
        $this->post('/portfolio', $this->valid(['title' => 'My-App']))->assertRedirect();

        $slugs = Portfolio::pluck('slug');
        $this->assertCount(2, $slugs->unique());
        $this->assertContains('my-app', $slugs);
    }

    public function test_edit_renames_replaces_and_removes_image(): void
    {
        $this->actingAs($this->admin());
        $this->post('/portfolio', $this->valid(['image' => $this->png()]));
        $project = Portfolio::firstOrFail();
        $first = $project->image;

        $this->get("/portfolio/{$project->slug}/edit")->assertOk()->assertSee('Remove this image');

        // Rename + replace image: old file goes, slug follows the title.
        $this->put("/portfolio/{$project->slug}", $this->valid(['title' => 'Home lab v2', 'image' => $this->png()]))
            ->assertRedirect(route('portfolio.single', 'home-lab-v2'));
        $project->refresh();
        $this->uploaded[] = $project->image;
        $this->assertFileDoesNotExist(public_path($first));
        $this->assertFileExists(public_path($project->image));

        // Saving without a new file keeps the image.
        $this->put('/portfolio/home-lab-v2', $this->valid(['title' => 'Home lab v2']));
        $this->assertSame($project->image, $project->fresh()->image);

        // Remove it.
        $this->put('/portfolio/home-lab-v2', $this->valid(['title' => 'Home lab v2', 'remove_image' => '1']));
        $this->assertNull($project->fresh()->image);
        $this->assertFileDoesNotExist(public_path($project->image));
    }

    public function test_renaming_to_an_existing_title_is_rejected(): void
    {
        $this->actingAs($this->admin());
        $this->post('/portfolio', $this->valid(['title' => 'One']));
        $this->post('/portfolio', $this->valid(['title' => 'Two']));

        $this->put('/portfolio/two', $this->valid(['title' => 'One']))->assertSessionHasErrors('title');
        // Keeping its own title is fine.
        $this->put('/portfolio/two', $this->valid(['title' => 'Two', 'summary' => 'Changed']))->assertSessionHasNoErrors();
    }

    public function test_delete_removes_project_and_image(): void
    {
        $this->actingAs($this->admin());
        $this->post('/portfolio', $this->valid(['image' => $this->png()]));
        $project = Portfolio::firstOrFail();

        $this->delete('/portfolio/homelab')->assertRedirect(route('portfolio'))->assertSessionHas('message', '“Homelab” deleted.');
        $this->assertSame(0, Portfolio::count());
        $this->assertFileDoesNotExist(public_path($project->image));

        // A project without an image deletes cleanly too.
        $this->post('/portfolio', $this->valid());
        $this->delete('/portfolio/homelab')->assertRedirect();
        $this->assertDirectoryExists(public_path());
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

    /** A real 1x1 PNG, so the image validation rule sees genuine image bytes. */
    private function png(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));

        return new UploadedFile($path, 'shot.png', 'image/png', null, true);
    }
}
