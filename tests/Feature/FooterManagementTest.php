<?php

namespace Tests\Feature;

use App\Models\SiteFooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_footer_content(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $payload = SiteFooter::defaultAttributes();
        $payload['cta_title'] = 'Need help today?';
        $payload['brand_blurb'] = 'Updated footer clinic summary.';
        $payload['link_groups'] = [
            [
                'title' => 'Resources',
                'source' => 'manual',
                'links' => [
                    [
                        'label' => 'Patient guide',
                        'href' => '/patient-guide',
                    ],
                ],
            ],
        ];
        $payload['social_links'] = [
            [
                'label' => 'Instagram',
                'href' => 'https://example.com/instagram',
                'icon' => 'instagram',
            ],
        ];

        $response = $this->actingAs($admin)->put('/admin/footer', $payload);

        $response->assertRedirect('/admin/footer');

        $footer = SiteFooter::current();

        $this->assertSame('Need help today?', $footer->cta_title);
        $this->assertSame('Updated footer clinic summary.', $footer->brand_blurb);
        $this->assertSame('Resources', $footer->link_groups[0]['title']);
        $this->assertSame('Patient guide', $footer->link_groups[0]['links'][0]['label']);
        $this->assertSame('https://example.com/instagram', $footer->social_links[0]['href']);
    }

    public function test_public_pages_receive_dynamic_footer_shared_prop(): void
    {
        SiteFooter::current()->update([
            'brand_name' => 'Dynamic Footer',
            'bottom_location' => 'Khar West, Mumbai',
            'link_groups' => [
                [
                    'title' => 'Resources',
                    'source' => 'manual',
                    'links' => [
                        [
                            'label' => 'Patient guide',
                            'href' => '/patient-guide',
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dynamic Footer', false);
        $response->assertSee('Khar West, Mumbai', false);
        $response->assertSee('Patient guide', false);
    }
}
