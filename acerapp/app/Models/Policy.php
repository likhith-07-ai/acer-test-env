<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'icon',
        'title',
        'content',
        'tagline',
        'status',
        'is_restricted',
        'file_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_restricted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * User who created the policy
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the policy
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Audit logs for this policy
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Scope for published policies
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for public policies (published and not restricted)
     */
    public function scopePublic($query)
    {
        return $query->where('status', 'published')
            ->where('is_restricted', false);
    }

    /**
     * Scope for restricted policies
     */
    public function scopeRestricted($query)
    {
        return $query->where('is_restricted', true);
    }

    /**
     * Check if policy is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if policy is public
     */
    public function isPublic(): bool
    {
        return $this->isPublished() && !$this->is_restricted;
    }

    /**
     * Check if policy is restricted
     */
    public function isRestricted(): bool
    {
        return $this->is_restricted === true;
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
                        $text = htmlspecialchars_decode($text, ENT_QUOTES);
                        $text = nl2br($text);
                        $text = strip_tags($text, '<b><strong><i><em><u><a><code><mark><br>');
                        $html .= '<p class="mb-4 leading-relaxed">' . $text . '</p>';
                        break;
                        
                    case 'header':
                        $text = $blockData['text'] ?? '';
                        $level = $blockData['level'] ?? 2;
                        $text = htmlspecialchars_decode($text, ENT_QUOTES);
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
                }
            }
            
            return $html;
        } catch (\Exception $e) {
            // If parsing fails, return as plain text
            return nl2br(e($this->content));
        }
    }
}
