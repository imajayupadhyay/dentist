<?php

namespace Tests\Feature;

use App\Models\AboutPage;
use App\Models\User;
use Database\Seeders\AboutPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_uses_cms_content_and_server_rendered_seo(): void
    {
        $this->seed(AboutPageSeeder::class);

        $response = $this->get('/about-us');

        $response->assertStatus(200);
        $response->assertSee('Sixteen years of', false);
        $response->assertSee('property="og:type"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('AboutPage', false);
    }

    public function test_admin_can_update_about_page_content(): void
    {
        $this->seed(AboutPageSeeder::class);

        $admin = User::factory()->create(['is_admin' => true]);
        $payload = AboutPage::defaultAttributes();
        $payload['masthead_eyebrow'] = 'Clinic background';
        $payload['note_body'] = '<p><strong>Updated</strong> founder note.</p>';
        $payload['seo_title'] = 'Updated about SEO title';

        $response = $this->actingAs($admin)->put('/admin/about', $payload);

        $response->assertRedirect('/admin/about');

        $this->assertDatabaseHas('about_pages', [
            'key' => AboutPage::KEY,
            'masthead_eyebrow' => 'Clinic background',
            'seo_title' => 'Updated about SEO title',
        ]);

        $this->assertSame(
            '<p><strong>Updated</strong> founder note.</p>',
            AboutPage::current()->note_body,
        );
    }
}
