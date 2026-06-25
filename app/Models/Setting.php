<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    public static function get(string $key, $default = null)
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            return \Illuminate\Support\Facades\Cache::remember('site_setting_' . $key, 600, function () use ($key, $default) {
                return static::where('key', $key)->value('value') ?? $default;
            });
        }
        return $default;
    }

    public static function set(string $key, $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
        \Illuminate\Support\Facades\Cache::forget('site_setting_' . $key);
        \Illuminate\Support\Facades\Cache::forget('site_settings');
    }
}
