<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SettingType; // We'll create this enum
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'value',
        'type',
        'description',
        'group',
        'is_private',
    ];

    // Cast 'type' to our custom Enum
    protected $casts = [
        'type' => SettingType::class,
        'is_private' => 'boolean',
    ];

    /**
     * Get a setting's value, applying dynamic casting based on its 'type'.
     */
    public function getValueAttribute($value)
    {
        if ($this->type instanceof SettingType) {
            return match ($this->type) {
                SettingType::Boolean => (bool) $value,
                SettingType::Integer => (int) $value,
                SettingType::Json => json_decode($value, true),
                // For 'string', 'text', 'email', 'url', 'image', 'color', 'password', just return the string
                default => $value,
            };
        }
        return $value; // Fallback if type is not set or not an enum instance
    }

    /**
     * Accessor to get the display name for the setting.
     * Falls back to humanizing the key if label is not set.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->label ?? Str::title(str_replace('_', ' ', $this->key));
    }

    /**
     * Set a setting's value, applying dynamic encoding based on its 'type'.
     */
    public function setValueAttribute($value)
    {
        if ($this->type instanceof SettingType) {
            $this->attributes['value'] = match ($this->type) {
                SettingType::Boolean => (string) (bool) $value,
                SettingType::Integer => (string) (int) $value,
                SettingType::Json => json_encode($value),
                default => (string) $value, // Convert everything else to string for storage
            };
        } else {
            $this->attributes['value'] = (string) $value;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope for settings belonging to a specific group.
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope for public (non-private) settings.
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Helper Methods (for easy access)
    |--------------------------------------------------------------------------
    */

    /**
     * Get a setting by its key.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        // If the setting exists and has a custom cast type, return its dynamically casted value
        if ($setting) {
            return $setting->value;
        }

        return $default;
    }

    /**
     * Set/Update a setting by its key.
     */
    public static function set(string $key, $value, string $type = 'string', string $label = null, string $description = null, string $group = null, bool $isPrivate = false)
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value; // The mutator will handle type casting before saving
        $setting->type = $type;
        $setting->label = $label;
        if ($description) $setting->description = $description;
        if ($group) $setting->group = $group;
        $setting->is_private = $isPrivate;
        $setting->save();

        return $setting;
    }
}
