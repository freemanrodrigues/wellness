<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    use HasFactory;

    protected $table = 'order_invoice';

    protected $fillable = [
        'user_id',
        'totalamount',
        'orderdiscount',
        'promo_discount',
        'deliverycharge',
        'gateway_id',
        'orderstatus',
        'sess_id',
        'shopflag',
        'affiliate_id',
        'affiliate_commission',
        'error_code',
        'error_message',
        'cardname',
        'cardnumber',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'affiliate_id' => 'integer',
        'totalamount' => 'decimal:2',
        'orderdiscount' => 'decimal:2',
        'promo_discount' => 'decimal:2',
        'deliverycharge' => 'decimal:2',
        'affiliate_commission' => 'decimal:2',
    ];

    /**
     * Relationship: User (customer) who placed the order invoice
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Items in this order invoice (fin_basket)
     */
    public function items()
    {
        return $this->hasMany(FinBasket::class, 'order_id');
    }
}
