<?php

namespace Tests\Feature;

use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_contact_form_submission_is_stored(): void
    {
        $preferredDate = now()->addDay()->toDateString();

        $response = $this->from('/')->post('/contact-submissions', [
            'name' => 'Priya Nair',
            'phone' => '+91 98200 00000',
            'email' => 'priya@example.com',
            'treatment' => 'Smile design',
            'preferred_date' => $preferredDate,
            'preferred_time' => 'Morning · 9:30 – 13:00',
            'message' => 'Please call me.',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('contact_submissions', [
            'name' => 'Priya Nair',
            'phone' => '+91 98200 00000',
            'email' => 'priya@example.com',
            'treatment' => 'Smile design',
            'preferred_date' => $preferredDate,
            'preferred_time' => 'Morning · 9:30 – 13:00',
            'message' => 'Please call me.',
            'source_page' => 'home',
            'status' => ContactSubmission::STATUS_NEW,
        ]);
    }

    public function test_homepage_contact_form_requires_name_and_phone(): void
    {
        $response = $this->from('/')->post('/contact-submissions', [
            'name' => '',
            'phone' => '',
        ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHasErrors(['name', 'phone']);
    }

    public function test_admin_can_view_contact_submissions(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        ContactSubmission::query()->create([
            'name' => 'Priya Nair',
            'phone' => '+91 98200 00000',
            'email' => 'priya@example.com',
            'status' => ContactSubmission::STATUS_NEW,
        ]);

        $response = $this->actingAs($admin)->get('/admin/contacts');

        $response
            ->assertStatus(200)
            ->assertSee('Priya Nair', false)
            ->assertSee('priya@example.com', false);
    }

    public function test_admin_can_update_contact_submission_status_and_notes(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $submission = ContactSubmission::query()->create([
            'name' => 'Priya Nair',
            'phone' => '+91 98200 00000',
            'status' => ContactSubmission::STATUS_NEW,
        ]);

        $response = $this->actingAs($admin)->patch("/admin/contacts/{$submission->id}", [
            'status' => ContactSubmission::STATUS_CONTACTED,
            'admin_notes' => 'Called once and left a message.',
        ]);

        $response->assertRedirect('/admin/contacts');

        $this->assertDatabaseHas('contact_submissions', [
            'id' => $submission->id,
            'status' => ContactSubmission::STATUS_CONTACTED,
            'admin_notes' => 'Called once and left a message.',
        ]);
    }
}
