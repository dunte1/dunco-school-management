<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Setting keys whose values are encrypted at rest.
     *
     * @var list<string>
     */
    public const SECRET_KEYS = [
        'smtp_pass',
        'sms_api_key',
        'api_token',
        'fcm_server_key',
        'africastalking_api_key',
        'payment_api_key',
        'paypal_secret',
        'mpesa_consumer_secret',
        'mpesa_passkey',
    ];

    public function isSecretKey(?string $key = null): bool
    {
        $key = $key ?? ($this->attributes['key'] ?? null);

        return $key !== null && in_array($key, self::SECRET_KEYS, true);
    }

    /**
     * Decrypt secret values on read (falls back to the raw value for legacy
     * plaintext rows).
     */
    public function getValueAttribute($value)
    {
        if ($value !== null && $this->isSecretKey()) {
            try {
                return Crypt::decryptString($value);
            } catch (\Throwable $e) {
                return $value;
            }
        }

        return $value;
    }

    /**
     * Encrypt secret values before persisting them.
     */
    public function setValueAttribute($value)
    {
        if ($value !== null && $value !== '' && $this->isSecretKey()) {
            $value = Crypt::encryptString((string) $value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get setting value with proper type casting
     */
    public function getTypedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return (bool) $this->value;
            case 'integer':
                return (int) $this->value;
            case 'json':
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }
}
