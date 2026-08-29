<?php

namespace Tests\Feature;

use App\Models\HomePage;
use App\Models\User;
use Database\Seeders\HomePageSeeder;
use Database\Seeders\TreatmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_cms_content_and_server_rendered_seo(): void
    {
        $this->seed(HomePageSeeder::class);
        $this->seed(TreatmentSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Your best smile', false);
        $response->assertSee('property="og:type"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('Dentist', false);
    }

    public function test_admin_can_update_homepage_content(): void
    {
        $this->seed(HomePageSeeder::class);

        $admin = User::factory()->create(['is_admin' => true]);
        $payload = HomePage::defaultAttributes();
        $payload['about_eyebrow'] = 'Clinic story';
        $payload['about_body'] = '<p><strong>Updated</strong> intro copy.</p>';
        $payload['seo_title'] = 'Updated home SEO title';

        $response = $this->actingAs($admin)->put('/admin/home', $payload);

        $response->assertRedirect('/admin/home');

        $this->assertDatabaseHas('home_pages', [
            'key' => HomePage::KEY,
            'about_eyebrow' => 'Clinic story',
            'seo_title' => 'Updated home SEO title',
        ]);

        $this->assertSame(
            '<p><strong>Updated</strong> intro copy.</p>',
            HomePage::current()->about_body,
        );
    }
}
