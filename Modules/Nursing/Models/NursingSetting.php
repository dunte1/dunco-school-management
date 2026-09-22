<?php

namespace Modules\Nursing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NursingSetting extends Model
{
    protected $table = 'nursing_settings';

    protected $fillable = [
        'school_id',
        'key',
        'value',
        'type',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    public static function getValue(string $key, $default = null, int $schoolId = null)
    {
        $query = static::where('key', $key);
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        $setting = $query->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value, int $schoolId = null): void
    {
        static::updateOrCreate(
            ['key' => $key, 'school_id' => $schoolId],
            ['value' => $value]
        );
    }
}
