<?php
// app/Http/Controllers/SettingController.php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = CompanySetting::first();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string',
            'company_address' => 'nullable|string',
            'company_website' => 'nullable|string',
            'company_tax_number' => 'nullable|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'currency' => 'required|string',
            'currency_symbol' => 'required|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = CompanySetting::first();
        
        if ($request->hasFile('company_logo')) {
            if ($settings->company_logo) {
                Storage::disk('public')->delete($settings->company_logo);
            }
            $validated['company_logo'] = $request->file('company_logo')->store('company', 'public');
        }

        $settings->update($validated);

        return redirect()->back()
            ->with('success', 'Settings updated successfully.');
    }
}