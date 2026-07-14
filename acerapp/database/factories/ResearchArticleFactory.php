<?php

namespace Database\Factories;

use App\Models\ResearchArticle;
use App\Models\ResearchCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResearchArticle>
 */
class ResearchArticleFactory extends Factory
{
    protected $model = ResearchArticle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'category_id' => ResearchCategory::factory(),
            'author_id' => User::factory(),
            'status' => 'draft',
            'is_restricted' => false,
            'views_count' => 0,
            'sort_order' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now()->subDay(),
            'is_restricted' => false,
        ]);
    }

    public function restricted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_restricted' => true,
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);
    }
}
