<?php

namespace App\Services\Interface;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Collection;

interface StockServiceInterface
{
    public function getAllMovements(): Collection;
    public function getMovementById(int $id): StockMovement;
    public function createStockIn(array $data): StockMovement;
    public function createStockOut(array $data): StockMovement;
    public function createStockAdjustment(array $data): StockMovement;
    public function getMovementsByProduct(int $productId): Collection;
    public function getMovementsByDateRange(string $startDate, string $endDate): Collection;
    public function getRecentMovements(int $limit = 10): Collection;
    public function updateProductStock(int $productId, int $newStock, string $type, array $additionalData = []): StockMovement;
    public function getLowStockProducts(): Collection;
    public function getStockSummary(): array;
    public function getMovementStatistics(): array;
}
