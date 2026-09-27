<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaqQuestionAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'faq_and_question_answers';

    protected $fillable = [
        'type',
        'product_id',
        'category_id',
        'subcategory_id',
        'brand_id',
        'page_name',
        'user_id',
        'user_name',
        'email',
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'product_id' => 'integer',
        'category_id' => 'integer',
        'subcategory_id' => 'integer',
        'brand_id' => 'integer',
        'user_id' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeFaq($query)
    {
        return $query->where('type', 'faq');
    }

    public function scopeQuestion($query)
    {
        return $query->where('type', 'question');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePending($query)
    {
        return $query->where('type', 'question')->whereNull('answer');
    }

    // ─── Helper Methods ───────────────────────────────────

    /**
     * Get answered visitor questions for a specific product.
     */
    public static function getQuestionsForProduct($productId)
    {
        return static::question()
            ->active()
            ->where('product_id', $productId)
            ->whereNotNull('answer')
            ->where('answer', '!=', '')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Get active FAQs for a specific product.
     */
    public static function getFaqsForProduct($productId)
    {
        return static::faq()
            ->active()
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get active FAQs for a category.
     */
    public static function getFaqsForCategory($categoryId)
    {
        return static::faq()
            ->active()
            ->where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get active FAQs for a subcategory.
     */
    public static function getFaqsForSubcategory($subcategoryId)
    {
        return static::faq()
            ->active()
            ->where('subcategory_id', $subcategoryId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get active FAQs for a brand.
     */
    public static function getFaqsForBrand($brandId)
    {
        return static::faq()
            ->active()
            ->where('brand_id', $brandId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get active FAQs for a specific static page (home, about, contact, terms, privacy, etc.).
     */
    public static function getFaqsForPage($pageName)
    {
        return static::faq()
            ->active()
            ->where('page_name', $pageName)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
