<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_users_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/users');
        
        $response->assertStatus(200);
        $response->assertSee('Users');
    }

    /** @test */
    public function admin_can_create_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'author',
        ]);
        
        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'author',
        ]);
    }

    /** @test */
    public function admin_can_update_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'author']);
        
        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'reviewer',
        ]);
        
        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'role' => 'reviewer',
        ]);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'author']);
        
        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");
        
        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function admin_cannot_delete_own_account()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /** @test */
    public function user_can_be_created_with_different_roles()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $roles = ['admin', 'author', 'reviewer', 'super_admin', 'public'];
        
        foreach ($roles as $role) {
            $response = $this->actingAs($admin)->post('/admin/users', [
                'name' => "Test {$role}",
                'email' => "{$role}@example.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => $role,
            ]);
            
            $response->assertRedirect('/admin/users');
            $this->assertDatabaseHas('users', [
                'email' => "{$role}@example.com",
                'role' => $role,
            ]);
        }
    }

    /** @test */
    public function user_password_is_hashed_when_created()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'author',
        ]);
        
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function user_password_can_be_updated()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'author']);
        
        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'role' => $user->role,
        ]);
        
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /** @test */
    public function user_password_can_be_left_unchanged()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'author']);
        $oldPassword = $user->password;
        
        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => $user->role,
        ]);
        
        $user->refresh();
        $this->assertEquals($oldPassword, $user->password);
    }
}
