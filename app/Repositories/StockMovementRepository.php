<?php

namespace App\Repositories;

use App\Models\StockMovement;
use App\Repositories\Interface\StockMovementRepositoryInterface;
use Illuminate\Support\Collection;

final class StockMovementRepository implements StockMovementRepositoryInterface
{
    public function all(): Collection
    {
        return StockMovement::with(['product', 'user'])->orderBy('movement_date', 'desc')->get();
    }

    public function findById(int $id): StockMovement
    {
        return StockMovement::with(['product', 'user'])->findOrFail($id);
    }

    public function create(array $data): StockMovement
    {
        return StockMovement::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $stockMovement = $this->findById($id);
        return $stockMovement->update($data);
    }

    public function delete(int $id): bool
    {
        $stockMovement = $this->findById($id);
        return $stockMovement->delete();
    }

    public function getByProduct(int $productId): Collection
    {
        return StockMovement::with(['user'])
            ->where('product_id', $productId)
            ->orderBy('movement_date', 'desc')
            ->get();
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return StockMovement::with(['product', 'user'])
            ->whereBetween('movement_date', [$startDate, $endDate])
            ->orderBy('movement_date', 'desc')
            ->get();
    }

    public function getByType(string $type): Collection
    {
        return StockMovement::with(['product', 'user'])
            ->where('type', $type)
            ->orderBy('movement_date', 'desc')
            ->get();
    }

    public function getRecentMovements(int $limit = 10): Collection
    {
        return StockMovement::with(['product', 'user'])
            ->orderBy('movement_date', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTotalMovementsByType(): array
    {
        return StockMovement::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }
}
