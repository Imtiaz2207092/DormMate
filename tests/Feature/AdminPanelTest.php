<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_access_admin_dashboard()
    {
        $student = User::factory()->create(['user_type' => 'student']);

        $response = $this->actingAs($student)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_admin_can_view_users_list()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);
        $student = User::factory()->create(['user_type' => 'student']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee($admin->name);
    }

    public function test_admin_cannot_delete_self()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'You cannot delete the currently logged in admin.');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_other_user()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);
        $student = User::factory()->create(['user_type' => 'student']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $student->id));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status', 'User deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $student->id]);
    }

    public function test_admin_redirected_to_admin_dashboard_upon_login()
    {
        $admin = User::factory()->create([
            'user_type' => 'admin',
            'password' => bcrypt('password')
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password'
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_student_redirected_to_student_dashboard_upon_login()
    {
        $student = User::factory()->create([
            'user_type' => 'student',
            'password' => bcrypt('password')
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'password'
        ]);

        $response->assertRedirect(route('dashboard'));
    }
}
