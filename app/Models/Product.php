<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'short_name',
        'vendor_product_name',
        'short_description',
        'description',
        'info',
        'use_case',
        'price',
        'discount',
        'deliverycharge',
        'isactive',
        'imgurl',
        'metatitle',
        'metadesc',
        'metakeyword',
        'metaurl',
        'cid',
        'vid',
        'cat_id',
        'subcat_id',
        'brand_id',
        'vendor_code',
        'sku',
        'barcode',
        'model_number',
        'manufacturer_part_number',
        'vendorprice',
        'vendordeliveryprice',
        'more_price',
        'more_img',
        'ratingvalue',
        'reviewcount',
        'viewed',
    ];

    protected $casts = [
        'price'               => 'decimal:2',
        'discount'            => 'decimal:2',
        'deliverycharge'      => 'decimal:2',
        'vendorprice'         => 'decimal:2',
        'vendordeliveryprice' => 'decimal:2',
        'more_price'          => 'decimal:2',
        'ratingvalue'         => 'decimal:2',
        'isactive'            => 'boolean',
        'reviewcount'         => 'integer',
        'viewed'              => 'integer',
    ];

    /**
     * Accessor: discounted selling price
     */
    public function getSellingPriceAttribute(): float
    {
        return max(0, $this->price - $this->discount);
    }

    /**
     * The health concerns associated with this product.
     */
    public function healthConcerns()
    {
        return $this->belongsToMany(HealthConcern::class, 'health_concern_products')
                    ->withPivot('sort_order')
                    ->withTimestamps();
    }
}
