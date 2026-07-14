<?php

namespace Tests\Feature;

use App\Models\ResearchArticle;
use App\Models\ResearchCategory;
use App\Models\ResearchTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResearchArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_research_articles_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ResearchArticle::factory()->count(3)->create();
        
        $response = $this->actingAs($admin)->get('/admin/research-articles');
        
        $response->assertStatus(200);
        $response->assertSee('Research Articles');
    }

    /** @test */
    public function super_admin_can_view_research_articles_list()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        ResearchArticle::factory()->count(3)->create();
        
        $response = $this->actingAs($superAdmin)->get('/admin/research-articles');
        
        $response->assertStatus(200);
        $response->assertSee('Research Articles');
    }

    /** @test */
    public function author_can_view_own_research_articles()
    {
        $author = User::factory()->create(['role' => 'author']);
        ResearchArticle::factory()->create(['author_id' => $author->id]);
        ResearchArticle::factory()->create(['author_id' => User::factory()->create()->id]);
        
        $response = $this->actingAs($author)->get('/admin/research-articles');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_research_article()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = ResearchCategory::factory()->create();
        
        Storage::fake('public');
        
        $response = $this->actingAs($admin)->post('/admin/research-articles', [
            'title' => 'Test Article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'category_id' => $category->id,
            'status' => 'draft',
            'is_restricted' => false,
        ]);
        
        $response->assertRedirect('/admin/research-articles');
        $this->assertDatabaseHas('research_articles', [
            'title' => 'Test Article',
            'author_id' => $admin->id,
        ]);
    }

    /** @test */
    public function author_can_create_research_article()
    {
        $author = User::factory()->create(['role' => 'author']);
        $category = ResearchCategory::factory()->create();
        
        $response = $this->actingAs($author)->post('/admin/research-articles', [
            'title' => 'Author Article',
            'excerpt' => 'Author excerpt',
            'content' => 'Author content',
            'category_id' => $category->id,
            'status' => 'draft',
            'is_restricted' => false,
        ]);
        
        $response->assertRedirect('/admin/research-articles');
        $this->assertDatabaseHas('research_articles', [
            'title' => 'Author Article',
            'author_id' => $author->id,
        ]);
    }

    /** @test */
    public function admin_can_approve_article()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'submitted',
        ]);
        
        $response = $this->actingAs($admin)->post("/admin/research-articles/{$article->id}/approve");
        
        $response->assertRedirect('/admin/research-articles/approval/queue');
        $this->assertDatabaseHas('research_articles', [
            'id' => $article->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
        ]);
    }

    /** @test */
    public function reviewer_can_approve_article()
    {
        $reviewer = User::factory()->create(['role' => 'reviewer']);
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'submitted',
        ]);
        
        $response = $this->actingAs($reviewer)->post("/admin/research-articles/{$article->id}/approve");
        
        $response->assertRedirect('/admin/research-articles/approval/queue');
        $this->assertDatabaseHas('research_articles', [
            'id' => $article->id,
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function reviewer_can_reject_article()
    {
        $reviewer = User::factory()->create(['role' => 'reviewer']);
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'submitted',
        ]);
        
        $response = $this->actingAs($reviewer)->post("/admin/research-articles/{$article->id}/reject", [
            'rejection_reason' => 'Not suitable for publication',
        ]);
        
        $response->assertRedirect('/admin/research-articles/approval/queue');
        $this->assertDatabaseHas('research_articles', [
            'id' => $article->id,
            'status' => 'rejected',
            'rejection_reason' => 'Not suitable for publication',
        ]);
    }

    /** @test */
    public function super_admin_can_publish_article()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'approved',
        ]);
        
        $response = $this->actingAs($superAdmin)->post("/admin/research-articles/{$article->id}/publish");
        
        $response->assertRedirect('/admin/research-articles');
        $this->assertDatabaseHas('research_articles', [
            'id' => $article->id,
            'status' => 'published',
            'published_by' => $superAdmin->id,
        ]);
    }

    /** @test */
    public function admin_can_publish_article()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'approved',
        ]);
        
        $response = $this->actingAs($admin)->post("/admin/research-articles/{$article->id}/publish");
        
        $response->assertRedirect('/admin/research-articles');
        $this->assertDatabaseHas('research_articles', [
            'id' => $article->id,
            'status' => 'published',
        ]);
    }

    /** @test */
    public function author_cannot_approve_own_article()
    {
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'submitted',
        ]);
        
        $response = $this->actingAs($author)->post("/admin/research-articles/{$article->id}/approve");
        
        $response->assertStatus(403);
    }

    /** @test */
    public function author_cannot_publish_article()
    {
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'approved',
        ]);
        
        $response = $this->actingAs($author)->post("/admin/research-articles/{$article->id}/publish");
        
        $response->assertStatus(403);
    }

    /** @test */
    public function reviewer_cannot_publish_article()
    {
        $reviewer = User::factory()->create(['role' => 'reviewer']);
        $author = User::factory()->create(['role' => 'author']);
        $article = ResearchArticle::factory()->create([
            'author_id' => $author->id,
            'status' => 'approved',
        ]);
        
        $response = $this->actingAs($reviewer)->post("/admin/research-articles/{$article->id}/publish");
        
        $response->assertStatus(403);
    }

    /** @test */
    public function public_articles_are_visible_to_public()
    {
        $article = ResearchArticle::factory()->create([
            'status' => 'published',
            'is_restricted' => false,
            'published_at' => now()->subDay(),
        ]);
        
        $response = $this->get('/public/research/articles');
        
        $response->assertStatus(200);
        $response->assertSee($article->title);
    }

    /** @test */
    public function restricted_articles_are_not_visible_to_public()
    {
        $article = ResearchArticle::factory()->create([
            'status' => 'published',
            'is_restricted' => true,
            'published_at' => now()->subDay(),
        ]);
        
        $response = $this->get('/public/research/articles');
        
        $response->assertStatus(200);
        $response->assertDontSee($article->title);
    }

    /** @test */
    public function draft_articles_are_not_visible_to_public()
    {
        $article = ResearchArticle::factory()->create([
            'status' => 'draft',
            'is_restricted' => false,
        ]);
        
        $response = $this->get('/public/research/articles');
        
        $response->assertStatus(200);
        $response->assertDontSee($article->title);
    }
}
