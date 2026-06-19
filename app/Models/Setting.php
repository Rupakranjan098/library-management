<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static $cachedSettings = null;

    protected static function loadSettings()
    {
        if (self::$cachedSettings === null) {
            try {
                self::$cachedSettings = self::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                self::$cachedSettings = [];
            }
        }
    }

    public static function get($key, $default = null)
    {
        self::loadSettings();
        return self::$cachedSettings[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value]);
        if (self::$cachedSettings !== null) {
            self::$cachedSettings[$key] = $value;
        }
        return $setting;
    }
}
