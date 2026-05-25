<?php
// database/seeders/CompanySettingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::create([
            'company_name' => 'SmartERP Solutions',
            'company_email' => 'info@smarterp.com',
            'company_phone' => '+1 234 567 8900',
            'company_address' => '123 Business Avenue, Suite 100, New York, NY 10001, USA',
            'company_website' => 'www.smarterp.com',
            'company_tax_number' => 'TAX-123456789',
            'timezone' => 'America/New_York',
            'date_format' => 'm/d/Y',
            'currency' => 'USD',
            'currency_symbol' => '$',
        ]);
    }
}