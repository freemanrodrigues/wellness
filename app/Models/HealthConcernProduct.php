<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthConcernProduct extends Model
{
    use HasFactory;

    protected $table = 'health_concern_products';

    protected $fillable = [
        'product_id',
        'health_concern_id',
        'sort_order',
    ];

    protected $casts = [
        'product_id'        => 'integer',
        'health_concern_id' => 'integer',
        'sort_order'        => 'integer',
    ];

    /**
     * Get the product associated with this record.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the health concern associated with this record.
     */
    public function healthConcern(): BelongsTo
    {
        return $this->belongsTo(HealthConcern::class);
    }
}
