<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'fullname',
        'phone',
        'role'
    ];
    public $timestamps = false;

}
