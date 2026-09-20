<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function getVal(string $key, $default = null)
    {
        $s = static::where('key', $key)->first();
        return $s ? $s->value : $default;
    }

    public static function setVal(string $key, $value, string $type = 'text'): static
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $type]);
    }

    public static function getAll(): array
    {
        return static::pluck('value', 'key')->toArray();
    }
}
