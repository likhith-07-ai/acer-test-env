<?php

namespace App\View\Components;

use App\Models\ResearchArticle;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ResearchArticles extends Component
{
    public $articles;
    public $limit;
    public $categoryId;
    public $title;
    public $showViewAll;
    public $titleClass;
    public $description;

    /**
     * Create a new component instance.
     */
    public function __construct($limit = 6, $category = null, $title = 'Research Articles', $showViewAll = true, $titleClass = '', $description = null)
    {
        $this->limit = (int) $limit;
        $this->categoryId = $category;
        $this->title = $title;
        $this->showViewAll = $showViewAll;
        $this->titleClass = $titleClass;
        $this->description = $description;

        // Fetch only public articles (published, not restricted, published_at <= now)
        // Handle gracefully if table doesn't exist (e.g., during tests)
        try {
            $query = ResearchArticle::public()
                ->with(['category', 'author', 'tags'])
                ->orderBy('published_at', 'desc');

            // Filter by category if provided
            if ($this->categoryId) {
                $query->where('category_id', $this->categoryId);
            }

            $this->articles = $query->limit($this->limit)->get();
        } catch (\Exception $e) {
            // If table doesn't exist or any database error, return empty collection
            $this->articles = collect();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.research-articles');
    }
}
