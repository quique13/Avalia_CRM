<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Hooking extends Eloquent
{
    protected $fillable = [
        'date', 'user_name', 'email', 'apartment', 'document_name', 'client_name', 'client_mail'
    ];
    public $timestamps = false;
}