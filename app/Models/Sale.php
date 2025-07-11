<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'customer_id',
        'user_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate unique transaction ID
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->transaction_id)) {
                $sale->transaction_id = 'TRX-' . date('Ymd') . '-' . str_pad((string)random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the customer for this sale
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who processed this sale
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for this sale
     */
    public function salesItems(): HasMany
    {
        return $this->hasMany(SalesItem::class);
    }

    /**
     * Get formatted transaction ID for display
     */
    public function getFormattedTransactionIdAttribute(): string
    {
        return $this->transaction_id;
    }

    /**
     * Get formatted payment method for display
     */
    public function getFormattedPaymentMethodAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Tunai',
            'card' => 'Kartu',
            'transfer' => 'Transfer',
            'qris' => 'QRIS',
            default => ucfirst($this->payment_method),
        };
    }

    /**
     * Get formatted status for display
     */
    public function getFormattedStatusAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
