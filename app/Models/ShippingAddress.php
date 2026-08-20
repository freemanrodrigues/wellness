<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingAddress extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipping_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        's_firstname',
        's_lastname',
        's_address1',
        's_address2',
        's_landmark',
        's_city',
        's_state',
        's_pincode',
        's_country_id',
        's_email',
        's_phone',
        'is_primary',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id'      => 'integer',
        's_country_id' => 'integer',
        'is_primary'   => 'boolean',
        'is_delete'    => 'boolean',
    ];

    /**
     * Get the user that owns the shipping address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
