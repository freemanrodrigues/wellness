<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'countryname',
        'shortname',
        'isocode',
        'tel_countrycode',
        'active',
        'gmt',
        'currencycode',
        'currencyrate',
        'timezoneid',
        'isocode3',
        'currencysign',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active'       => 'boolean',
        'currencyrate' => 'float',
        'timezoneid'   => 'integer',
    ];
}
