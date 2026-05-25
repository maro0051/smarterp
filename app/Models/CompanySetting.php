<?php
// app/Models/CompanySetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_logo',
        'company_email',
        'company_phone',
        'company_address',
        'company_website',
        'company_tax_number',
        'timezone',
        'date_format',
        'currency',
        'currency_symbol',
    ];
}