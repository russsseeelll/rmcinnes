<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiContextTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_llms_txt_summarises_the_site_from_the_database(): void
    {
        $this->actingAs(User::create(['name' => 'admin', 'password' => bcrypt('x')]))
            ->putJson('/api/profile', ['ai_summary' => 'I specialise in platform engineering & <observability>.'])->assertOk();

        $this->get('/llms.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('# Alex Example', false)
            ->assertSee('I specialise in platform engineering & <observability>.', false)
            ->assertSee('### Senior Platform Engineer, Example Corp', false)
            ->assertSee('Homelab Kubernetes cluster')
            ->assertSee(route('cv'));
    }

    public function test_homepage_has_valid_person_structured_data(): void
    {
        $html = $this->get('/')->assertOk()->assertSee('rel="alternate" type="text/plain"', false)->getContent();

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $ld = json_decode($m[1] ?? '', true);

        $this->assertSame('Person', $ld['@type']);
        $this->assertSame('Alex Example', $ld['name']);
        $this->assertContains('Kubernetes', $ld['knowsAbout']);
        $this->assertNotEmpty($ld['hasCredential']);
    }
}
