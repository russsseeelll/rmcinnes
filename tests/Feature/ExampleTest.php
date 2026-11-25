<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_pages_render_from_the_database(): void
    {
        $this->seed();
        $project = Portfolio::create([
            'title' => 'Test project', 'date' => '2030-01-01', 'category' => 'Platform',
            'tags' => 'Kubernetes, Proxmox', 'summary' => 'A test summary.', 'description' => 'A test description.',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Alex Example')
            ->assertSee(Experience::first()->title)
            ->assertSee($project->title);

        $this->get('/portfolio')->assertOk()->assertSee($project->title);
        $this->get('/portfolio?category='.$project->category)->assertOk()->assertSee($project->title);
        $this->get('/portfolio/'.$project->slug)->assertOk()->assertSee($project->summary);
        $this->get('/login')->assertOk();
    }

    public function test_home_renders_before_seeding(): void
    {
        $this->get('/')->assertOk()->assertSee('Your Name');
    }
}
