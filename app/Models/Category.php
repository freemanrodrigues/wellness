<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
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
        'status' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────

    /** Parent category */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /** Direct children */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /** All nested descendants (recursive) */
    public function allChildren(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->children()->with('allChildren');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeVisibleInMenu($query)
    {
        return $query->where('show_in_menu', true)->where('status', true);
    }

    // ─── Helpers ─────────────────────────────────────────

    /** Auto-generate slug from name if not set (scoped by parent_id) */
    public static function generateSlug(string $name, ?int $ignoreId = null, mixed $parentId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        $parentId = !empty($parentId) ? (int) $parentId : null;

        $checkSlugExists = function ($slugToCheck) use ($parentId, $ignoreId) {
            $query = static::where('slug', $slugToCheck);
            if ($parentId !== null) {
                $query->where('parent_id', $parentId);
            } else {
                $query->whereNull('parent_id');
            }
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            return $query->exists();
        };

        while ($checkSlugExists($slug)) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /**
     * Fetch top 5 parent categories and up to 8 subcategories for each category.
     * Returns a 2-dimensional associative array containing 'name', 'slug', and 'subcategories'.
     *
     * @param int $limitCategories Default 5
     * @param int $limitSubcategories Default 8
     * @return array
     */
    public static function getNavbarCategories(int $limitCategories = 5, int $limitSubcategories = 8): array
    {
        $parentCategories = static::query()
            ->where(function ($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->where('status', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->take($limitCategories)
            ->get(['id', 'name', 'slug']);

        return $parentCategories->map(function ($cat) use ($limitSubcategories) {
            $subcategories = static::query()
                ->where('parent_id', $cat->id)
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->take($limitSubcategories)
                ->get(['name', 'slug'])
                ->toArray();

            return [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'subcategories' => $subcategories,
            ];
        })->toArray();
    }

    /**
     * Get the top-level categories (max 4, ordered by sort_order) along with
     * their subcategories, grouped by parent category id.
     *
     * @return array{categories: \Illuminate\Support\Collection, subcategories: array}
     */
    public static function getTopCategoriesWithSubcategories(): array
    {
        $categories = DB::table('categories')
            ->select('id', 'name', 'slug')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $subcategoryRows = DB::table('categories')
            ->select('parent_id', 'name', 'slug')
            ->whereNotNull('parent_id')
            ->get();

        $subcategories = [];
        foreach ($subcategoryRows as $subcat) {
            $subcategories[$subcat->parent_id][] = [
                'name' => $subcat->name,
                'slug' => $subcat->slug,
            ];
        }

        return [
            'categories' => $categories,
            'subcategories' => $subcategories,
        ];
    }

    public static function getCategoryId($slug)
    {
        $cat_id = DB::table('categories')->where('slug', $slug)->first()->id;
        return $cat_id;
    }
    public static function getSubCategoryId($category, $slug)
    {
        //->where('parent_id', $category)
        $cat_id = DB::table('categories')->where('slug', $slug)->first()->id;
        return $cat_id;
    }
}

