<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class StockOpname extends Model
{
    protected $fillable = [
        'opname_number',
        'title',
        'description',
        'status',
        'opname_date',
        'created_by',
        'completed_by',
        'started_at',
        'completed_at',
        'total_products',
        'counted_products',
        'total_variance_value',
    ];

    protected $casts = [
        'opname_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_variance_value' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockOpnameDetail::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->total_products == 0) {
            return 0;
        }

        return round(($this->counted_products / $this->total_products) * 100, 2);
    }

    public function getFormattedTotalVarianceValueAttribute(): string
    {
        $value = abs($this->total_variance_value);
        $formatted = 'Rp ' . number_format($value, 0, ',', '.');

        if ($this->total_variance_value > 0) {
            return '+' . $formatted;
        } elseif ($this->total_variance_value < 0) {
            return '-' . $formatted;
        }

        return $formatted;
    }

    public function canBeStarted(): bool
    {
        return $this->status === 'draft';
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'in_progress' && $this->counted_products === $this->total_products;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['draft', 'in_progress']);
    }
}
