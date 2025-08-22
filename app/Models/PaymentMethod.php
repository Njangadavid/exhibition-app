<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'code',
        'type',
        'description',
        'config',
        'is_active',
        'is_default',
        'sort_order',
        'icon',
        'color',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get active payment methods ordered by sort order
     */
    public static function getActive()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get default payment method
     */
    public static function getDefault()
    {
        return static::where('is_default', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get payment methods by type
     */
    public static function getByType($type)
    {
        return static::where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Check if this is a card-based payment method
     */
    public function isCardBased()
    {
        return in_array($this->type, ['card', 'digital_wallet']);
    }

    /**
     * Check if this is a bank transfer method
     */
    public function isBankTransfer()
    {
        return $this->type === 'bank_transfer';
    }

    /**
     * Get gateway configuration value
     */
    public function getConfig($key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Set gateway configuration value
     */
    public function setConfig($key, $value)
    {
        $config = $this->config ?? [];
        $config[$key] = $value;
        $this->config = $config;
    }

    /**
     * Get public key for gateways like Paystack
     */
    public function getPublicKey()
    {
        return $this->getConfig('public_key');
    }

    /**
     * Get secret key for gateways like Paystack
     */
    public function getSecretKey()
    {
        return $this->getConfig('secret_key');
    }

    /**
     * Get webhook secret for gateways like Paystack
     */
    public function getWebhookSecret()
    {
        return $this->getConfig('webhook_secret');
    }

    /**
     * Check if gateway is in test mode
     */
    public function isTestMode()
    {
        return $this->getConfig('test_mode', false);
    }

    /**
     * Get gateway environment (live/test)
     */
    public function getEnvironment()
    {
        return $this->isTestMode() ? 'test' : 'live';
    }

    /**
     * Scope for active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default payment method
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for card-based payment methods
     */
    public function scopeCardBased($query)
    {
        return $query->whereIn('type', ['card', 'digital_wallet']);
    }

    /**
     * Scope for bank transfer methods
     */
    public function scopeBankTransfer($query)
    {
        return $query->where('type', 'bank_transfer');
    }

    /**
     * Get the event that owns this payment method
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
