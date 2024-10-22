<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class ReservationClient extends Eloquent
{
    protected $fillable = [
        'name', 'email', 'nit', 'dpi', 'dpiCreation', 'address', 'linePhone', 'cellPhone',
        'nationality', 'birthday', 'civilStatus', 'company', 'jobTitle', 'companyAddress',
        'monthlySalary', 'otherIncomeAmount', 'otherIncome', 'hasFHACredit', 'refNumber',
        'dpiPictureName', 'receiptPictureName', 'hookingId'
    ];

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'nit' => 'string',
        'dpi' => 'string',
        'dpiCreation' => 'string',
        'address' => 'string',
        'linePhone' => 'string',
        'cellPhone' => 'string',
        'nationality' => 'string',
        'birthday' => 'string',
        'civilStatus' => 'string',
        'company' => 'string',
        'jobTitle' => 'string',
        'companyAddress' => 'string',
        'monthlySalary' => 'double',
        'otherIncomeAmount' => 'double',
        'otherIncome' => 'string',
        'hasFHACredit' => 'boolean',
        'refNumber' => 'string',
        'dpiPictureName' => 'string',
        'receiptPictureName' => 'string',
        'hookingId' => 'int'
    ];
    public $timestamps = false;
}