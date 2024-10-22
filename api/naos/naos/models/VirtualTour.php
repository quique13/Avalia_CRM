<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class VirtualTour extends Eloquent
{
    protected $fillable = [
        'username',
        'password'
    ];
    public $timestamps = false;
}
