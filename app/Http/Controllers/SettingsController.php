<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display a listing of the settings.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Store and update settings.
     */
    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                $setting->value = $value;
                $setting->save();

                // Clear cache for this setting
                Cache::forget("setting.{$key}");
            }
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Toggle a boolean setting.
     */
    public function toggle(Request $request, $key)
    {
        $setting = Setting::where('key', $key)->first();

        if ($setting && $setting->type === 'boolean') {
            $setting->value = !$setting->value;
            $setting->save();

            // Clear cache for this setting
            Cache::forget("setting.{$key}");

            return response()->json([
                'success' => true,
                'value' => $setting->value,
                'message' => 'Pengaturan berhasil diperbarui'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pengaturan tidak ditemukan'
        ], 404);
    }
}
