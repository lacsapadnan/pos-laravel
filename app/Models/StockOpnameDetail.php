<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class StockOpnameDetail extends Model
{
    protected $fillable = [
        'stock_opname_id',
        'product_id',
        'system_stock',
        'physical_stock',
        'variance',
        'unit_cost',
        'variance_value',
        'notes',
        'is_counted',
        'counted_by',
        'counted_at',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'variance_value' => 'decimal:2',
        'is_counted' => 'boolean',
        'counted_at' => 'datetime',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function countedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    public function getVarianceDisplayAttribute(): string
    {
        if ($this->variance === null) {
            return '-';
        }

        $sign = $this->variance > 0 ? '+' : '';
        return $sign . number_format($this->variance);
    }

    public function getVarianceColorAttribute(): string
    {
        if ($this->variance === null || $this->variance === 0) {
            return 'secondary';
        }

        return $this->variance > 0 ? 'success' : 'danger';
    }

    public function getFormattedVarianceValueAttribute(): string
    {
        if ($this->variance_value === null) {
            return '-';
        }

        $value = abs($this->variance_value);
        $formatted = 'Rp ' . number_format($value, 0, ',', '.');

        if ($this->variance_value > 0) {
            return '+' . $formatted;
        } elseif ($this->variance_value < 0) {
            return '-' . $formatted;
        }

        return $formatted;
    }

    public function getFormattedUnitCostAttribute(): string
    {
        return 'Rp ' . number_format($this->unit_cost, 0, ',', '.');
    }

    public function calculateVariance(): void
    {
        if ($this->physical_stock !== null) {
            $this->variance = $this->physical_stock - $this->system_stock;
            $this->variance_value = $this->variance * $this->unit_cost;
        }
    }
}
