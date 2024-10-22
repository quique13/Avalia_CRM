<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Apartment extends Eloquent
{
    protected $fillable = [
        'level', 'codification', 'code', 'status', 'rooms', 'price','iusi_plus_asurance', 'sqmts', 'garden_mts', 'warehouse_mts', 'car_parking', 'bike_parking', 'warehouse_price', 'image_name', 'image_plant'
    ];

    protected $casts = [
        'level' => 'integer',
        'tower' => 'integer',
        'codification' => 'string',
        'status' => 'number',
        'rooms' => 'integer',
        'price' => 'double',
        'iusi_plus_asurance' => 'double',
        'sqmts' => 'double',
        'garden_mts' => 'double',
        'warehouse_mts' => 'double',
        'car_parking' => 'integer',
        'bike_parking' => 'integer',
        'warehouse_price' => 'double',
        'image_name' => 'string',
        'image_plant' => 'string'
    ];
    public $timestamps = false;
}