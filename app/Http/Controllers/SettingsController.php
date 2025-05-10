<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Clear all settings cache
        Setting::clearCache();

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Toggle a boolean setting.
     */
    public function toggle(Request $request, $key)
    {
        try {
            // Find the setting or create it if it doesn't exist
            $setting = Setting::firstOrNew(['key' => $key]);

            if (!$setting->exists) {
                // Initialize new setting
                $setting->key = $key;
                $setting->group = 'display';
                $setting->type = 'boolean';
                $setting->label = ucwords(str_replace('_', ' ', $key));
                $setting->description = 'Auto-created setting';
            }

            // Get value from request (0 or 1)
            $newValue = $request->input('value') ? '1' : '0';

            // Simple direct update to the database
            $setting->value = $newValue;
            $setting->save();

            // Clear cache
            Cache::forget("setting.{$key}");

            return response()->json([
                'success' => true,
                'value' => (bool) $newValue,
                'message' => 'Pengaturan berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error toggling setting {$key}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all settings cache manually
     */
    public function clearCache()
    {
        Setting::clearCache();
        return redirect()->back()->with('success', 'Cache pengaturan berhasil dihapus.');
    }

    /**
     * Initialize default settings if they don't exist
     */
    public function initializeSettings()
    {
        try {
            $defaults = [
                [
                    'key' => 'show_featured_badge',
                    'value' => '1',
                    'group' => 'display',
                    'type' => 'boolean',
                    'label' => 'Tampilkan Badge Unggulan',
                    'description' => 'Tampilkan badge "Unggulan" pada produk unggulan di halaman shop',
                ],
                [
                    'key' => 'site_name',
                    'value' => 'Urbanova',
                    'group' => 'general',
                    'type' => 'text',
                    'label' => 'Nama Situs',
                    'description' => 'Nama situs web Anda',
                ],
            ];

            $created = 0;
            foreach ($defaults as $default) {
                if (!Setting::where('key', $default['key'])->exists()) {
                    Setting::create($default);
                    $created++;
                }
            }

            // Clear all settings cache
            Setting::clearCache();

            return redirect()->route('settings.index')
                ->with('success', "Berhasil menginisialisasi {$created} pengaturan default.");

        } catch (\Exception $e) {
            Log::error('Error initializing settings: ' . $e->getMessage());
            return redirect()->route('settings.index')
                ->with('error', 'Gagal menginisialisasi pengaturan: ' . $e->getMessage());
        }
    }
}
