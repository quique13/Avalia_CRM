<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Contact extends Eloquent
{
    protected $fillable = [
        'name', 'email', 'phone', 'message', 'date'
    ];
    public $timestamps = false;
}
