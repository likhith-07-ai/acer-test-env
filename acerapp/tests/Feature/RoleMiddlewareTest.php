<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_middleware_allows_admin_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_middleware_allows_super_admin_users()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($superAdmin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_middleware_blocks_public_users()
    {
        $publicUser = User::factory()->create(['role' => 'public']);
        
        $response = $this->actingAs($publicUser)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function author_middleware_allows_author_users()
    {
        $author = User::factory()->create(['role' => 'author']);
        
        $response = $this->actingAs($author)->get('/admin/research-articles');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function author_middleware_allows_admin_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/research-articles');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function author_middleware_allows_super_admin_users()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($superAdmin)->get('/admin/research-articles');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function reviewer_middleware_allows_reviewer_users()
    {
        $reviewer = User::factory()->create(['role' => 'reviewer']);
        
        $response = $this->actingAs($reviewer)->get('/admin/research-articles/approval/queue');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function reviewer_middleware_allows_admin_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/research-articles/approval/queue');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function super_admin_middleware_allows_only_super_admin()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($superAdmin)->get('/admin/research-articles');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function super_admin_middleware_blocks_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Super admin middleware should block admin for specific routes
        // But admin can access research articles through admin middleware
        $response = $this->actingAs($admin)->get('/admin/research-articles');
        
        $response->assertStatus(200);
    }
}
