<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinBasket extends Model
{
    use HasFactory;

    protected $table = 'fin_basket';

    protected $fillable = [
        'order_id',
        'pid',
        'cid',
        'vid',
        'product_name',
        'qty',
        'product_price',
        'vendor_price',
        'pdiscount',
        'deliverycharge',
        'vendor_deliverycharge',
        'sess_id',
        'user_email',
        'user_id',
        'user_ip',
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
        'cardmessage',
        'deliverydate',
        'basketflag',
    ];

    protected $casts = [
        'qty' => 'integer',
        'pid' => 'integer',
        'cid' => 'integer',
        'vid' => 'integer',
        's_country_id' => 'integer',
        'user_id' => 'integer',
        'product_price' => 'decimal:2',
        'vendor_price' => 'decimal:2',
        'pdiscount' => 'decimal:2',
        'deliverycharge' => 'decimal:2',
        'vendor_deliverycharge' => 'decimal:2',
        'deliverydate' => 'date',
    ];

    /**
     * Relationship: Product details
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'pid');
    }

    /**
     * Relationship: Associated Order Invoice
     */
    public function invoice()
    {
        return $this->belongsTo(OrderInvoice::class, 'order_id', 'gateway_id');
    }
}
