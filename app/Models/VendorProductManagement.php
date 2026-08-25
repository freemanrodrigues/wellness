<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorProductManagement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vendor_product_management';

    protected $fillable = [
        'name',
        'description',
        'info',
        'price',
        'imgurl',
        'vid',
        'cat_id',
        'subcat_id',
        'brand_id',
        'vendor_code',
        'status',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'status'    => 'boolean',
        'vid'       => 'integer',
        'cat_id'    => 'integer',
        'subcat_id' => 'integer',
        'brand_id'  => 'integer',
    ];

    /**
     * Relationship with Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    /**
     * Relationship with Subcategory
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcat_id');
    }

    /**
     * Relationship with Brand
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Scope for active vendor products
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
