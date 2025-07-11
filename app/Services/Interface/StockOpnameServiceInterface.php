<?php

namespace App\Services\Interface;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Illuminate\Support\Collection;

interface StockOpnameServiceInterface
{
    public function getAllOpnames(): Collection;
    public function getOpnameById(int $id): StockOpname;
    public function createOpname(array $data): StockOpname;
    public function updateOpname(int $id, array $data): bool;
    public function deleteOpname(int $id): bool;
    public function startOpname(int $id): bool;
    public function completeOpname(int $id): bool;
    public function cancelOpname(int $id): bool;
    public function addProductsToOpname(int $opnameId, array $productIds = []): int;
    public function updateProductCount(int $opnameId, int $productId, int $physicalStock, ?string $notes = null): StockOpnameDetail;
    public function generateOpnameNumber(): string;
    public function getOpnameWithDetails(int $id): StockOpname;
    public function processOpnameCompletion(int $id): array;
}
