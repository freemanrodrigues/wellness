<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pid',
        'cid',
        'vid',
        'Productname',
        'qty',
        'prodprice',
        'vendor_price',
        'multiple_price_id',
        'mutiple_price_desc',
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
        'ocassionid',
        'locationtype',
        'deliverydate',
        'basketflag',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pid'                   => 'integer',
        'cid'                   => 'integer',
        'vid'                   => 'integer',
        'qty'                   => 'integer',
        'prodprice'             => 'decimal:2',
        'vendor_price'          => 'decimal:2',
        'multiple_price_id'     => 'integer',
        'pdiscount'             => 'decimal:2',
        'deliverycharge'        => 'decimal:2',
        'vendor_deliverycharge' => 'decimal:2',
        'user_id'               => 'integer',
        's_country_id'          => 'integer',
        'ocassionid'            => 'integer',
        'deliverydate'          => 'date',
    ];
}
