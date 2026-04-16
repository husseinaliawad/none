<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'homepage_show_trending' => ['nullable', 'boolean'],
            'homepage_show_latest' => ['nullable', 'boolean'],
            'homepage_show_recommended' => ['nullable', 'boolean'],
            'footer_links' => ['nullable', 'string'],
            'legal_pages' => ['nullable', 'string'],
        ]);

        $payload['maintenance_mode'] = (bool) ($payload['maintenance_mode'] ?? false);
        $payload['homepage_show_trending'] = (bool) ($payload['homepage_show_trending'] ?? false);
        $payload['homepage_show_latest'] = (bool) ($payload['homepage_show_latest'] ?? false);
        $payload['homepage_show_recommended'] = (bool) ($payload['homepage_show_recommended'] ?? false);

        foreach ($payload as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]);
        }

        return back()->with('status', 'Settings updated successfully.');
    }
}
