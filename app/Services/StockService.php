<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\StockMovementRepositoryInterface;
use App\Services\Interface\StockServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class StockService implements StockServiceInterface
{
    protected StockMovementRepositoryInterface $stockMovementRepository;
    protected ProductRepositoryInterface $productRepository;

    public function __construct(
        StockMovementRepositoryInterface $stockMovementRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->stockMovementRepository = $stockMovementRepository;
        $this->productRepository = $productRepository;
    }

    public function getAllMovements(): Collection
    {
        return $this->stockMovementRepository->all();
    }

    public function getMovementById(int $id): StockMovement
    {
        return $this->stockMovementRepository->findById($id);
    }

    public function createStockIn(array $data): StockMovement
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);
            $previousStock = $product->stock;
            $newStock = $previousStock + $data['quantity'];

            // Update product stock
            $this->productRepository->update($product->id, ['stock' => $newStock]);

            // Create stock movement record
            return $this->stockMovementRepository->create([
                'product_id' => $data['product_id'],
                'type' => 'in',
                'quantity' => $data['quantity'],
                'previous_stock' => $previousStock,
                'current_stock' => $newStock,
                'unit_cost' => $data['unit_cost'] ?? null,
                'total_cost' => isset($data['unit_cost']) ? $data['unit_cost'] * $data['quantity'] : null,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'user_id' => Auth::id(),
                'movement_date' => $data['movement_date'] ?? now(),
            ]);
        });
    }

    public function createStockOut(array $data): StockMovement
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);
            $previousStock = $product->stock;
            $newStock = $previousStock - $data['quantity'];

            if ($newStock < 0) {
                throw new \Exception('Stok tidak mencukupi. Stok saat ini: ' . $previousStock);
            }

            // Update product stock
            $this->productRepository->update($product->id, ['stock' => $newStock]);

            // Create stock movement record
            return $this->stockMovementRepository->create([
                'product_id' => $data['product_id'],
                'type' => 'out',
                'quantity' => -$data['quantity'], // Negative for stock out
                'previous_stock' => $previousStock,
                'current_stock' => $newStock,
                'unit_cost' => $data['unit_cost'] ?? null,
                'total_cost' => isset($data['unit_cost']) ? $data['unit_cost'] * $data['quantity'] : null,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'user_id' => Auth::id(),
                'movement_date' => $data['movement_date'] ?? now(),
            ]);
        });
    }

    public function createStockAdjustment(array $data): StockMovement
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);
            $previousStock = $product->stock;
            $newStock = $data['new_stock'];
            $adjustment = $newStock - $previousStock;

            // Update product stock
            $this->productRepository->update($product->id, ['stock' => $newStock]);

            // Create stock movement record
            return $this->stockMovementRepository->create([
                'product_id' => $data['product_id'],
                'type' => 'adjustment',
                'quantity' => $adjustment,
                'previous_stock' => $previousStock,
                'current_stock' => $newStock,
                'unit_cost' => $data['unit_cost'] ?? null,
                'total_cost' => isset($data['unit_cost']) ? $data['unit_cost'] * abs($adjustment) : null,
                'reference_type' => 'adjustment',
                'reference_id' => null,
                'notes' => $data['notes'] ?? null,
                'user_id' => Auth::id(),
                'movement_date' => $data['movement_date'] ?? now(),
            ]);
        });
    }

    public function getMovementsByProduct(int $productId): Collection
    {
        return $this->stockMovementRepository->getByProduct($productId);
    }

    public function getMovementsByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->stockMovementRepository->getByDateRange($startDate, $endDate);
    }

    public function getRecentMovements(int $limit = 10): Collection
    {
        return $this->stockMovementRepository->getRecentMovements($limit);
    }

    public function updateProductStock(int $productId, int $newStock, string $type, array $additionalData = []): StockMovement
    {
        $data = array_merge([
            'product_id' => $productId,
            'new_stock' => $newStock,
        ], $additionalData);

        return match ($type) {
            'adjustment' => $this->createStockAdjustment($data),
            'in' => $this->createStockIn(array_merge($data, ['quantity' => $newStock - $this->productRepository->findById($productId)->stock])),
            'out' => $this->createStockOut(array_merge($data, ['quantity' => $this->productRepository->findById($productId)->stock - $newStock])),
            default => throw new \InvalidArgumentException('Invalid stock movement type: ' . $type),
        };
    }

    public function getLowStockProducts(): Collection
    {
        return $this->productRepository->all()->filter(function ($product) {
            return $product->stock <= $product->minimum_stock;
        });
    }

    public function getStockSummary(): array
    {
        $products = $this->productRepository->all();

        return [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(fn($p) => $p->stock * $p->price),
            'low_stock_count' => $this->getLowStockProducts()->count(),
            'out_of_stock_count' => $products->where('stock', 0)->count(),
            'total_stock_quantity' => $products->sum('stock'),
        ];
    }

    public function getMovementStatistics(): array
    {
        $movements = $this->stockMovementRepository->getTotalMovementsByType();

        return [
            'total_movements' => array_sum($movements),
            'stock_in' => $movements['in'] ?? 0,
            'stock_out' => $movements['out'] ?? 0,
            'adjustments' => $movements['adjustment'] ?? 0,
            'opnames' => $movements['opname'] ?? 0,
        ];
    }
}
