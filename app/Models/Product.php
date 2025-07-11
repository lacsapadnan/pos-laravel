<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'category_id',
        'unit_id',
        'price',
        'stock',
        'minimum_stock',
        'image',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Aktif' : 'Tidak Aktif';
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Habis';
        } elseif ($this->stock <= $this->minimum_stock) {
            return 'Stok Rendah';
        } else {
            return 'Tersedia';
        }
    }
}
