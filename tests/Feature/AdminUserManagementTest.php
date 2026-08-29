<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_page(): void
    {
        $admin = User::factory()->create([
            'name' => 'Primary Admin',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response
            ->assertStatus(200)
            ->assertSee('Primary Admin', false);
    }

    public function test_admin_can_create_another_admin_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Second Admin',
            'email' => 'second@example.com',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
        ]);

        $response->assertRedirect('/admin/users');

        $created = User::query()->where('email', 'second@example.com')->firstOrFail();

        $this->assertTrue($created->is_admin);
        $this->assertTrue(Hash::check('SecurePass123', $created->password));
    }

    public function test_admin_can_update_admin_profile_and_password(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create([
            'name' => 'Old Admin',
            'email' => 'old@example.com',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$target->id}", [
            'name' => 'Updated Admin',
            'email' => 'updated@example.com',
            'password' => 'NewSecurePass123',
            'password_confirmation' => 'NewSecurePass123',
        ]);

        $response->assertRedirect('/admin/users');

        $target->refresh();

        $this->assertSame('Updated Admin', $target->name);
        $this->assertSame('updated@example.com', $target->email);
        $this->assertTrue($target->is_admin);
        $this->assertTrue(Hash::check('NewSecurePass123', $target->password));
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $response
            ->assertRedirect()
            ->assertSessionHas('error', 'You cannot delete your own admin account.');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'is_admin' => true,
        ]);
    }

    public function test_admin_can_delete_another_admin_when_one_remains(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$target->id}");

        $response->assertRedirect('/admin/users');

        $this->assertDatabaseMissing('users', [
            'id' => $target->id,
        ]);
    }
}
