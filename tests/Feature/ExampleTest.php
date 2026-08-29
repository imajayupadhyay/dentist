<?php

namespace Tests\Feature;

use Database\Seeders\TreatmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(TreatmentSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_seeded_treatment_detail_page_returns_a_successful_response(): void
    {
        $this->seed(TreatmentSeeder::class);

        $response = $this->get('/treatments/dental-implants');

        $response->assertStatus(200);
        $response->assertSee('Dental Implants - Dr. Pushpa Patel', false);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('MedicalProcedure', false);
    }
}
