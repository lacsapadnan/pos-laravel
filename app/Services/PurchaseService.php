<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Services\Interface\PurchaseServiceInterface;
use App\Repositories\Interface\PurchaseRepositoryInterface;
use App\Repositories\Interface\StockMovementRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\SettingRepositoryInterface;

final class PurchaseService implements PurchaseServiceInterface
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly StockMovementRepositoryInterface $stockMovementRepository,
        private readonly SettingRepositoryInterface $settingRepository
    ) {}

    public function getAll(): iterable
    {
        return $this->purchaseRepository->all();
    }

    public function getById(int $id): ?Purchase
    {
        return $this->purchaseRepository->findWithItems($id);
    }

    public function create(array $data, array $items): Purchase
    {
        return DB::transaction(function () use ($data, $items) {
            // Generate kode unik pembelian
            $today = now()->format('Ymd');
            $last = Purchase::whereDate('date', now()->toDateString())->orderByDesc('id')->first();
            $lastNumber = $last && $last->code ? (int)substr($last->code, -4) : 0;
            $nextNumber = str_pad((string)($lastNumber + 1), 4, '0', STR_PAD_LEFT);
            $data['code'] = 'PB-' . $today . '-' . $nextNumber;
            $data['total'] = 0; // Set default agar tidak error
            $purchase = $this->purchaseRepository->create($data);
            $total = 0;
            $setting = $this->settingRepository->getSettings();
            $margin = $setting?->margin ?? 0;
            foreach ($items as $item) {
                $item['purchase_id'] = $purchase->id;
                $item['subtotal'] = $item['quantity'] * $item['price'];
                PurchaseItem::create($item);
                // Update stok produk
                Product::where('id', $item['product_id'])->increment('stock', $item['quantity']);
                $total += $item['subtotal'];
                // Update harga modal & harga jual produk
                $product = Product::find($item['product_id']);
                $product->capital_price = $item['price'];
                $product->price = $item['price'] + ($item['price'] * ($margin / 100));
                $product->save();
                // Catat stok masuk ke StockMovement
                $previousStock = $product->stock - $item['quantity'];
                $movementDate = isset($data['date']) ? now()->setDateFrom($data['date']) : now();
                $this->stockMovementRepository->create([
                    'product_id' => $item['product_id'],
                    'type' => 'in',
                    'quantity' => $item['quantity'],
                    'previous_stock' => $previousStock,
                    'current_stock' => $product->stock,
                    'unit_cost' => $item['price'],
                    'total_cost' => $item['subtotal'],
                    'reference_type' => 'purchase',
                    'reference_id' => $purchase->id,
                    'notes' => 'Pembelian - ' . $data['code'],
                    'user_id' => Auth::id(),
                    'movement_date' => $movementDate,
                ]);
            }
            $purchase->update(['total' => $total]);
            return $purchase->load(['supplier', 'items.product']);
        });
    }

    public function update(int $id, array $data, array $items): bool
    {
        return DB::transaction(function () use ($id, $data, $items) {
            $purchase = $this->purchaseRepository->findWithItems($id);
            if (!$purchase) return false;
            // Rollback stok lama
            foreach ($purchase->items as $oldItem) {
                Product::where('id', $oldItem->product_id)->decrement('stock', $oldItem->quantity);
                $oldItem->delete();
            }
            $total = 0;
            foreach ($items as $item) {
                $item['purchase_id'] = $purchase->id;
                $item['subtotal'] = $item['quantity'] * $item['price'];
                PurchaseItem::create($item);
                Product::where('id', $item['product_id'])->increment('stock', $item['quantity']);
                $total += $item['subtotal'];
            }
            $data['total'] = $total;
            return $purchase->update($data);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $purchase = $this->purchaseRepository->findWithItems($id);
            if (!$purchase) return false;
            // Rollback stok dan hapus stock movement
            foreach ($purchase->items as $item) {
                $product = Product::find($item->product_id);
                $previousStock = $product->stock;
                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                $currentStock = $previousStock - $item->quantity;
                // Catat stock movement rollback
                $this->stockMovementRepository->create([
                    'product_id' => $item->product_id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'previous_stock' => $previousStock,
                    'current_stock' => $currentStock,
                    'unit_cost' => $item->price,
                    'total_cost' => $item->subtotal,
                    'reference_type' => 'purchase-delete',
                    'reference_id' => $purchase->id,
                    'notes' => 'Rollback stok karena hapus pembelian',
                    'user_id' => Auth::id(),
                    'movement_date' => now(),
                ]);
            }
            return $this->purchaseRepository->delete($id);
        });
    }
}
