<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ResearchArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'author_id',
        'reviewed_by',
        'published_by',
        'status',
        'is_restricted',
        'published_at',
        'reviewed_at',
        'rejection_reason',
        'views_count',
        'sort_order',
    ];

    protected $casts = [
        'is_restricted' => 'boolean',
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'views_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title, $article->id);
            }
        });
    }

    /**
     * Generate a unique slug from title
     */
    public static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists (excluding current article if updating)
        $query = static::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ResearchCategory::class, 'category_id');
    }

    /**
     * Get the author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the reviewer
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the publisher
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Get tags
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ResearchTag::class, 'research_article_tag', 'research_article_id', 'research_tag_id');
    }

    /**
     * Get media files
     */
    public function media(): HasMany
    {
        return $this->hasMany(ResearchArticleMedia::class, 'research_article_id')->orderBy('sort_order');
    }

    /**
     * Get meta data
     */
    public function meta(): HasMany
    {
        return $this->hasMany(ResearchArticleMeta::class, 'research_article_id');
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('is_restricted', false)
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for public articles (published and not restricted)
     */
    public function scopePublic($query)
    {
        return $query->where('status', 'published')
            ->where('is_restricted', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for approval queue
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Check if article is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' 
            && !$this->is_restricted 
            && $this->published_at !== null 
            && $this->published_at <= now();
    }

    /**
     * Check if article can be viewed by public
     */
    public function isPublic(): bool
    {
        return $this->isPublished() && !$this->is_restricted;
    }

    /**
     * Increment views count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Convert Editor.js JSON content to HTML
     */
    public function getRenderedContent(): string
    {
        if (empty($this->content)) {
            return '';
        }

        try {
            $data = json_decode($this->content, true);
            
            // If not valid JSON, return as plain text
            if (json_last_error() !== JSON_ERROR_NONE || !isset($data['blocks'])) {
                return nl2br(e($this->content));
            }

            $html = '';
            
            foreach ($data['blocks'] as $block) {
                $type = $block['type'] ?? '';
                $blockData = $block['data'] ?? [];
                
                switch ($type) {
                    case 'paragraph':
                        $text = $blockData['text'] ?? '';
                        // Convert HTML entities and preserve formatting
                        $text = htmlspecialchars_decode($text, ENT_QUOTES);
                        // Convert \n to <br>
                        $text = nl2br($text);
                        // Allow basic HTML tags like <b>, <strong>, <i>, <em>, <a>, etc.
                        $text = strip_tags($text, '<b><strong><i><em><u><a><code><mark><br>');
                        $html .= '<p class="mb-4 leading-relaxed">' . $text . '</p>';
                        break;
                        
                    case 'header':
                        $text = $blockData['text'] ?? '';
                        $level = $blockData['level'] ?? 2;
                        $text = htmlspecialchars_decode($text, ENT_QUOTES);
                        // Allow basic HTML tags
                        $text = strip_tags($text, '<b><strong><i><em><u><code><mark>');
                        $sizeClass = [
                            1 => 'text-4xl',
                            2 => 'text-3xl',
                            3 => 'text-2xl',
                            4 => 'text-xl',
                            5 => 'text-lg',
                            6 => 'text-base'
                        ];
                        $size = $sizeClass[$level] ?? 'text-2xl';
                        $html .= "<h{$level} class=\"{$size} font-bold mb-4 mt-6\">{$text}</h{$level}>";
                        break;
                        
                    case 'list':
                        $style = $blockData['style'] ?? 'unordered';
                        $items = $blockData['items'] ?? [];
                        $tag = $style === 'ordered' ? 'ol' : 'ul';
                        $html .= "<{$tag} class=\"list-disc list-inside mb-4 space-y-2\">";
                        foreach ($items as $item) {
                            $item = htmlspecialchars_decode($item, ENT_QUOTES);
                            $item = nl2br($item);
                            $html .= "<li>{$item}</li>";
                        }
                        $html .= "</{$tag}>";
                        break;
                        
                    case 'quote':
                        $text = $blockData['text'] ?? '';
                        $caption = $blockData['caption'] ?? '';
                        $text = htmlspecialchars_decode($text, ENT_QUOTES);
                        $text = nl2br($text);
                        $html .= '<blockquote class="border-l-4 border-primary-500 pl-4 py-2 my-4 italic text-gray-700">';
                        $html .= '<p>' . $text . '</p>';
                        if ($caption) {
                            $caption = htmlspecialchars_decode($caption, ENT_QUOTES);
                            $html .= '<cite class="text-sm text-gray-500 mt-2 block">— ' . $caption . '</cite>';
                        }
                        $html .= '</blockquote>';
                        break;
                        
                    case 'code':
                        $code = $blockData['code'] ?? '';
                        $code = htmlspecialchars($code);
                        $html .= '<pre class="bg-gray-100 p-4 rounded-lg overflow-x-auto mb-4"><code>' . $code . '</code></pre>';
                        break;
                        
                    case 'delimiter':
                        $html .= '<hr class="my-6 border-gray-300">';
                        break;
                        
                    case 'image':
                        $url = $blockData['url'] ?? '';
                        $caption = $blockData['caption'] ?? '';
                        if ($url) {
                            $html .= '<figure class="my-6">';
                            $html .= '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($caption ?: '') . '" class="w-full rounded-lg">';
                            if ($caption) {
                                $html .= '<figcaption class="text-sm text-gray-500 mt-2 text-center">' . htmlspecialchars($caption) . '</figcaption>';
                            }
                            $html .= '</figure>';
                        }
                        break;
                        
                    case 'attachment':
                        $url = $blockData['url'] ?? '';
                        $name = $blockData['name'] ?? 'Document';
                        $size = $blockData['size'] ?? 0;
                        if ($url) {
                            $fileSize = '';
                            if ($size) {
                                if ($size < 1024) {
                                    $fileSize = $size . ' B';
                                } elseif ($size < 1024 * 1024) {
                                    $fileSize = number_format($size / 1024, 1) . ' KB';
                                } else {
                                    $fileSize = number_format($size / (1024 * 1024), 1) . ' MB';
                                }
                            }
                            $html .= '<div class="my-6 border border-gray-300 rounded-lg p-4 bg-gray-50 flex items-center gap-4">';
                            $html .= '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-500"><path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2ZM18 20H6V4H13V9H18V20Z" fill="currentColor"/></svg>';
                            $html .= '<div class="flex-1">';
                            $html .= '<div class="font-medium text-gray-900">' . htmlspecialchars($name) . '</div>';
                            if ($fileSize) {
                                $html .= '<div class="text-sm text-gray-500">' . htmlspecialchars($fileSize) . '</div>';
                            }
                            $html .= '</div>';
                            $html .= '<a href="' . htmlspecialchars($url) . '" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Download</a>';
                            $html .= '</div>';
                        }
                        break;
                        
                    case 'linkTool':
                        $link = $blockData['link'] ?? '';
                        $meta = $blockData['meta'] ?? [];
                        if ($link) {
                            $title = $meta['title'] ?? $link;
                            $description = $meta['description'] ?? '';
                            $html .= '<div class="border border-gray-300 rounded-lg p-4 my-4">';
                            $html .= '<a href="' . htmlspecialchars($link) . '" target="_blank" class="text-primary-600 hover:text-primary-800 font-medium">' . htmlspecialchars($title) . '</a>';
                            if ($description) {
                                $html .= '<p class="text-sm text-gray-600 mt-2">' . htmlspecialchars($description) . '</p>';
                            }
                            $html .= '</div>';
                        }
                        break;
                        
                    case 'table':
                        $content = $blockData['content'] ?? [];
                        if (!empty($content)) {
                            $html .= '<div class="overflow-x-auto my-6">';
                            $html .= '<table class="min-w-full border border-gray-300">';
                            foreach ($content as $row) {
                                $html .= '<tr>';
                                foreach ($row as $cell) {
                                    $html .= '<td class="border border-gray-300 px-4 py-2">' . htmlspecialchars($cell) . '</td>';
                                }
                                $html .= '</tr>';
                            }
                            $html .= '</table>';
                            $html .= '</div>';
                        }
                        break;
                        
                    case 'checklist':
                        $items = $blockData['items'] ?? [];
                        if (!empty($items)) {
                            $html .= '<ul class="list-none my-4 space-y-2">';
                            foreach ($items as $item) {
                                $checked = $item['checked'] ?? false;
                                $text = htmlspecialchars($item['text'] ?? '');
                                $checkedClass = $checked ? 'line-through text-gray-500' : '';
                                $html .= '<li class="flex items-center gap-2">';
                                $html .= '<input type="checkbox" disabled ' . ($checked ? 'checked' : '') . ' class="w-4 h-4">';
                                $html .= '<span class="' . $checkedClass . '">' . $text . '</span>';
                                $html .= '</li>';
                            }
                            $html .= '</ul>';
                        }
                        break;
                        
                    case 'embed':
                    case 'videoEmbed':
                        $url = $blockData['url'] ?? '';
                        $embed = $blockData['embed'] ?? '';
                        if ($embed) {
                            $html .= '<div class="my-6 relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">';
                            $html .= '<iframe src="' . htmlspecialchars($embed) . '" frameborder="0" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>';
                            $html .= '</div>';
                        } elseif ($url) {
                            $html .= '<div class="my-6"><a href="' . htmlspecialchars($url) . '" target="_blank" class="text-blue-600 hover:underline">' . htmlspecialchars($url) . '</a></div>';
                        }
                        break;
                        
                    case 'alertBox':
                        $type = $blockData['type'] ?? 'info';
                        $message = $blockData['message'] ?? '';
                        if ($message) {
                            $colors = [
                                'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-500', 'text' => 'text-blue-800', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-500', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-500', 'text' => 'text-yellow-800', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                                'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-500', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z']
                            ];
                            $color = $colors[$type] ?? $colors['info'];
                            $message = nl2br(htmlspecialchars($message));
                            $html .= '<div class="my-6 border-2 ' . $color['border'] . ' rounded-lg p-4 ' . $color['bg'] . ' flex gap-3">';
                            $html .= '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0"><path d="' . $color['icon'] . '" fill="currentColor" class="' . $color['text'] . '"/></svg>';
                            $html .= '<div class="flex-1 ' . $color['text'] . '">' . $message . '</div>';
                            $html .= '</div>';
                        }
                        break;
                        
                    case 'gallery':
                        $images = $blockData['images'] ?? [];
                        if (!empty($images)) {
                            $html .= '<div class="my-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">';
                            foreach ($images as $img) {
                                $url = $img['url'] ?? '';
                                if ($url) {
                                    $html .= '<div class="aspect-square overflow-hidden rounded-lg border-2 border-gray-200">';
                                    $html .= '<img src="' . htmlspecialchars($url) . '" alt="Gallery image" class="w-full h-full object-cover">';
                                    $html .= '</div>';
                                }
                            }
                            $html .= '</div>';
                        }
                        break;
                        
                    case 'button':
                        $text = $blockData['text'] ?? 'Click here';
                        $url = $blockData['url'] ?? '#';
                        $style = $blockData['style'] ?? 'primary';
                        $styleClasses = [
                            'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
                            'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
                            'success' => 'bg-green-600 hover:bg-green-700 text-white',
                            'danger' => 'bg-red-600 hover:bg-red-700 text-white'
                        ];
                        $class = $styleClasses[$style] ?? $styleClasses['primary'];
                        $html .= '<div class="my-6">';
                        $html .= '<a href="' . htmlspecialchars($url) . '" target="_blank" class="inline-block px-6 py-3 rounded-lg font-medium transition-colors ' . $class . '">' . htmlspecialchars($text) . '</a>';
                        $html .= '</div>';
                        break;
                        
                    case 'accordion':
                        $items = $blockData['items'] ?? [];
                        if (!empty($items)) {
                            $html .= '<div class="my-6 border border-gray-300 rounded-lg overflow-hidden">';
                            foreach ($items as $index => $item) {
                                $title = htmlspecialchars($item['title'] ?? 'Title');
                                $content = nl2br(htmlspecialchars($item['content'] ?? ''));
                                $borderClass = $index < count($items) - 1 ? 'border-b border-gray-300' : '';
                                $html .= '<div class="' . $borderClass . '">';
                                $html .= '<div class="p-4 bg-gray-50 font-medium cursor-pointer" onclick="this.nextElementSibling.classList.toggle(\'hidden\')">' . $title . '</div>';
                                $html .= '<div class="p-4 hidden">' . $content . '</div>';
                                $html .= '</div>';
                            }
                            $html .= '</div>';
                        }
                        break;
                        
                    case 'tableOfContents':
                        $title = $blockData['title'] ?? 'Table of Contents';
                        // Note: TOC generation would need JavaScript on frontend
                        $html .= '<div class="my-6 border border-gray-300 rounded-lg p-4 bg-gray-50">';
                        $html .= '<h3 class="font-semibold mb-2">' . htmlspecialchars($title) . '</h3>';
                        $html .= '<p class="text-sm text-gray-600">Table of contents will be auto-generated from headings</p>';
                        $html .= '</div>';
                        break;
                        
                    case 'rawHTML':
                        $htmlContent = $blockData['html'] ?? '';
                        if ($htmlContent) {
                            // Note: Be careful with raw HTML - sanitize if needed
                            $html .= '<div class="my-6">' . $htmlContent . '</div>';
                        }
                        break;
                }
            }
            
            return $html;
        } catch (\Exception $e) {
            // If parsing fails, return as plain text
            return nl2br(e($this->content));
        }
    }
}
