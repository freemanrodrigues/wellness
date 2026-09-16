<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_form_mst';

    protected $fillable = [
        'product_form',
        'isactive',
    ];

    protected $casts = [
        'isactive' => 'boolean',
    ];

    /**
     * Scope for active product forms.
     */
    public function scopeActive($query)
    {
        return $query->where('isactive', true);
    }
}
