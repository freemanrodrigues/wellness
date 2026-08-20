<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'banner',
        'sort_order',
        'status',
        'show_in_menu',
        'show_on_homepage',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status'           => 'boolean',
        'show_in_menu'     => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order'       => 'integer',
    ];

    // ─── Scopes ───────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeVisibleInMenu($query)
    {
        return $query->where('show_in_menu', true)->where('status', true);
    }

    public function scopeVisibleOnHomepage($query)
    {
        return $query->where('show_on_homepage', true)->where('status', true);
    }

    // ─── Helpers ─────────────────────────────────────────

    /** Auto-generate slug from name if not set */
    public static function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;

        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug  = "{$base}-{$i}";
            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            $i++;
        }

        return $slug;
    }
}
