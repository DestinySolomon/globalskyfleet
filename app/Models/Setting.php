<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'description'];
    
    // Change this to true or remove it (default is true)
    public $timestamps = true;
    
    /**
     * Get setting value with proper casting
     */
    public function getValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            return (bool) $value;
        }
        
        if ($this->type === 'integer') {
            return (int) $value;
        }
        
        if ($this->type === 'decimal') {
            return (float) $value;
        }
        
        if ($this->type === 'json') {
            return json_decode($value, true) ?? [];
        }
        
        return $value;
    }
    
    /**
     * Set setting value with proper formatting
     */
    public function setValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            $this->attributes['value'] = $value ? '1' : '0';
        } elseif ($this->type === 'json') {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = (string) $value;
        }
    }
    
    /**
     * Get setting by key
     */
    public static function get($key, $default = null)
    {
        // Try to get from cache first
        $settings = Cache::remember('settings_cache', 3600, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
        
        return $settings[$key] ?? $default;
    }
    
    /**
     * Set setting by key
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        $setting->type = $type;
        $setting->group = $group;
        $setting->value = $value;
        
        if ($description) {
            $setting->description = $description;
        }
        
        $setting->save();
        
        // Clear cache after saving
        Cache::forget('settings_cache');
        
        return $setting;
    }
    
    /**
     * Get all settings grouped
     */
    public static function getAllGrouped()
    {
        return Cache::remember('settings_grouped', 3600, function () {
            return self::orderBy('group')->orderBy('key')->get()->groupBy('group');
        });
    }
}