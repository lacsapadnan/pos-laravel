<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'previous_stock',
        'current_stock',
        'unit_cost',
        'total_cost',
        'reference_type',
        'reference_id',
        'notes',
        'user_id',
        'movement_date',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'movement_date' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'in' => 'Masuk',
            'out' => 'Keluar',
            'adjustment' => 'Penyesuaian',
            'opname' => 'Stock Opname',
            default => $this->type,
        };
    }

    public function getQuantityDisplayAttribute(): string
    {
        $sign = in_array($this->type, ['in', 'adjustment']) && $this->quantity > 0 ? '+' : '';
        return $sign . number_format($this->quantity);
    }

    public function getFormattedUnitCostAttribute(): string
    {
        return $this->unit_cost ? 'Rp ' . number_format($this->unit_cost, 0, ',', '.') : '-';
    }

    public function getFormattedTotalCostAttribute(): string
    {
        return $this->total_cost ? 'Rp ' . number_format($this->total_cost, 0, ',', '.') : '-';
    }

    public function getReferenceDisplayAttribute(): string
    {
        if (!$this->reference_type) {
            return '-';
        }

        return match ($this->reference_type) {
            'purchase' => 'Pembelian #' . $this->reference_id,
            'sale' => 'Penjualan #' . $this->reference_id,
            'adjustment' => 'Penyesuaian Manual',
            'opname' => 'Stock Opname #' . $this->reference_id,
            default => $this->reference_type . ' #' . $this->reference_id,
        };
    }
}
