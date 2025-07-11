<?php

namespace App\Repositories\Interface;

use App\Models\StockMovement;
use Illuminate\Support\Collection;

interface StockMovementRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): StockMovement;
    public function create(array $data): StockMovement;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByProduct(int $productId): Collection;
    public function getByDateRange(string $startDate, string $endDate): Collection;
    public function getByType(string $type): Collection;
    public function getRecentMovements(int $limit = 10): Collection;
    public function getTotalMovementsByType(): array;
}
