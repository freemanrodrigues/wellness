<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'url',
        'excerpt',
        'description',
        'image',
        'tags',
        'blog_meta_title',
        'blog_meta_description',
        'cat_id',
        'status',
        'page_show',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'page_show' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopePageShow($query)
    {
        return $query->where('page_show', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    // ─── Helpers & Accessors ──────────────────────────────

    /**
     * Auto-generate unique URL slug from title if not set
     */
    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if (empty($base)) {
            $base = 'post';
        }
        $slug = $base;
        $i = 1;

        $query = static::where('url', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = "{$base}-{$i}";
            $query = static::where('url', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            $i++;
        }

        return $slug;
    }

    /**
     * Get tags as formatted array of hashtags
     */
    public function getFormattedTagsAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }

        // Split by space or comma
        $rawTags = preg_split('/[\s,]+/', $this->tags, -1, PREG_SPLIT_NO_EMPTY);
        $formatted = [];

        foreach ($rawTags as $tag) {
            $cleaned = trim($tag);
            if ($cleaned === '') continue;
            if (!str_starts_with($cleaned, '#')) {
                $cleaned = '#' . $cleaned;
            }
            $formatted[] = $cleaned;
        }

        return array_unique($formatted);
    }
}
