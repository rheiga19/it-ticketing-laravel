<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        // ensure default roles exist
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'superadmin']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertEquals('user', auth()->user()->role->name);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_admin_route_is_protected_by_role(): void
    {
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);

        $user = User::factory()->create(['role_id' => $userRole->id]);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $this->actingAs($user)->get('/admin')->assertStatus(403);
        $this->actingAs($admin)->get('/admin')->assertStatus(200);
    }
}
