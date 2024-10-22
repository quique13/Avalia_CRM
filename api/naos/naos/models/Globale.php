<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Globale extends Eloquent
{
    protected $fillable = [
        'rate',
        'extra_parking_price',
        'available_parking_spots',
        'dollar_exchange',
        'hooking_percentage',
        'warehouses',
        'iusi_rate',
        'insurance_rate',
        'invoicing_percentage',
        'projectdate',
        'kitchen_price_a',
        'kitchen_price_b'
    ];
    public $timestamps = false;
}
