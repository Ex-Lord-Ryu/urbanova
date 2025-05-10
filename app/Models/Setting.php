<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value', 'group', 'type', 'label', 'description'
    ];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // For critical settings like show_featured_badge, always get fresh value
        if ($key === 'show_featured_badge') {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        }

        // For other settings, use cache with shorter duration
        return Cache::remember("setting.{$key}", 60, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param array $attributes Additional attributes
     * @return Setting
     */
    public static function set(string $key, $value, array $attributes = [])
    {
        Cache::forget("setting.{$key}");

        $setting = static::updateOrCreate(
            ['key' => $key],
            array_merge(['value' => $value], $attributes)
        );

        return $setting;
    }

    /**
     * Cast value based on type
     *
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            return $value === '1' || $value === 1 || $value === true || $value === 'true';
        }

        if ($this->type === 'integer') {
            return (int) $value;
        }

        if ($this->type === 'array' || $this->type === 'json') {
            return json_decode($value, true);
        }

        return $value;
    }

    /**
     * Prepare value for storage
     */
    public function setValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            $this->attributes['value'] = $value ? '1' : '0';
        } else if ($this->type === 'array' || $this->type === 'json') {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = (string) $value;
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("setting.{$setting->key}");
        }
        return true;
    }
}
