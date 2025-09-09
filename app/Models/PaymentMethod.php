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
        'gateway',
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
     * Check if this is a Paystack payment method
     */
    public function isPaystack()
    {
        // Check explicit gateway first
        if ($this->gateway === 'paystack') {
            return true;
        }
        
        // Check if it's explicitly NOT Pesapal and has Paystack-style keys
        if ($this->gateway === 'pesapal' || $this->type === 'pesapal') {
            return false;
        }
        
        // Check for Paystack-specific configuration
        return $this->type === 'card' && 
               $this->getConfig('public_key') && 
               str_starts_with($this->getConfig('public_key'), 'pk_');
    }

    /**
     * Check if this is a Pesapal payment method
     */
    public function isPesapal()
    {
        // Check explicit gateway
        if ($this->gateway === 'pesapal') {
            return true;
        }
        
        // Check type
        if ($this->type === 'pesapal') {
            return true;
        }
        
        // Check Pesapal-specific configuration
        if ($this->getConfig('consumer_key') && $this->getConfig('consumer_secret')) {
            return true;
        }
        
        // Check standard configuration (public_key/secret_key) for Pesapal
        // Only if it's not explicitly Paystack and has the right type
        if (($this->type === 'card' || $this->type === 'digital_wallet') && 
            $this->getConfig('public_key') && $this->getConfig('secret_key') && 
            !str_starts_with($this->getConfig('public_key'), 'pk_')) {
            return true;
        }
        
        return false;
    }


    /**
     * Get the payment gateway name
     */
    public function getGatewayName()
    {
        if ($this->gateway) {
            return ucfirst($this->gateway);
        }

        // Auto-detect based on configuration
        if ($this->isPaystack()) {
            return 'Paystack';
        } elseif ($this->isPesapal()) {
            return 'Pesapal';
        } elseif ($this->isBankTransfer()) {
            return 'Bank Transfer';
        }

        return ucfirst($this->type);
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
     * Get consumer key for gateways like Pesapal
     */
    public function getConsumerKey()
    {
        return $this->getConfig('consumer_key');
    }

    /**
     * Get consumer secret for gateways like Pesapal
     */
    public function getConsumerSecret()
    {
        return $this->getConfig('consumer_secret');
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
