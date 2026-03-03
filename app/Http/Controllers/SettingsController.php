<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\View\View;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = [
            'base_charge' => SystemSetting::get('base_charge', 500),
            'usage_rate' => SystemSetting::get('usage_rate', 50),
            'alert_threshold' => SystemSetting::get('alert_threshold', 1000),
            'alert_email' => SystemSetting::get('alert_email', ''),
            'regular_green_max' => SystemSetting::get('regular_green_max', 12),
            'regular_orange_max' => SystemSetting::get('regular_orange_max', 14),
            'commercial_green_max' => SystemSetting::get('commercial_green_max', 12),
            'commercial_orange_max' => SystemSetting::get('commercial_orange_max', 14),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'base_charge' => 'required|numeric|min:0',
            'usage_rate' => 'required|numeric|min:0',
            'alert_threshold' => 'required|numeric|min:0',
            'alert_email' => 'required|email',
            'regular_green_max' => 'required|numeric|min:0',
            'regular_orange_max' => 'required|numeric|min:0',
            'commercial_green_max' => 'required|numeric|min:0',
            'commercial_orange_max' => 'required|numeric|min:0',
        ]);

        // Ensure the configured ranges make sense
        if ($validated['regular_orange_max'] < $validated['regular_green_max']) {
            return redirect()->back()
                ->withErrors(['regular_orange_max' => 'Regular orange max must be greater than or equal to regular green max'])
                ->withInput();
        }

        if ($validated['commercial_orange_max'] < $validated['commercial_green_max']) {
            return redirect()->back()
                ->withErrors(['commercial_orange_max' => 'Commercial orange max must be greater than or equal to commercial green max'])
                ->withInput();
        }

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value, 'number');
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
