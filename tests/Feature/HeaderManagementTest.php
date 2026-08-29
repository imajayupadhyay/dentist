<?php

namespace Tests\Feature;

use App\Models\SiteHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeaderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_header_with_dropdown_navigation(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $payload = SiteHeader::defaultAttributes();
        $payload['brand_name'] = 'Clinic Header';
        $payload['cta_label'] = 'Schedule visit';
        $payload['nav_items'] = [
            [
                'label' => 'Templates',
                'href' => '',
                'current_path' => '',
                'children' => [
                    [
                        'label' => 'Smile Design',
                        'href' => '/templates/smile-design',
                        'current_path' => '/templates/smile-design',
                    ],
                    [
                        'label' => 'Implants',
                        'href' => '/templates/implants',
                        'current_path' => '/templates/implants',
                    ],
                ],
            ],
            [
                'label' => 'Contact',
                'href' => '/#contact',
                'current_path' => '',
                'children' => [],
            ],
        ];

        $response = $this->actingAs($admin)->put('/admin/header', $payload);

        $response->assertRedirect('/admin/header');

        $header = SiteHeader::current();

        $this->assertSame('Clinic Header', $header->brand_name);
        $this->assertSame('Schedule visit', $header->cta_label);
        $this->assertSame('Templates', $header->nav_items[0]['label']);
        $this->assertSame('', $header->nav_items[0]['href']);
        $this->assertSame('Smile Design', $header->nav_items[0]['children'][0]['label']);
        $this->assertSame('/templates/smile-design', $header->nav_items[0]['children'][0]['href']);
    }

    public function test_public_pages_receive_dynamic_header_shared_prop(): void
    {
        SiteHeader::current()->update([
            'brand_name' => 'Dynamic Header',
            'nav_items' => [
                [
                    'label' => 'Templates',
                    'href' => '',
                    'current_path' => '',
                    'children' => [
                        [
                            'label' => 'Smile Design',
                            'href' => '/templates/smile-design',
                            'current_path' => '/templates/smile-design',
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dynamic Header', false);
        $response->assertSee('Templates', false);
        $response->assertSee('Smile Design', false);
    }
}
