<?php

namespace App\Repositories\Interface;

use App\Models\StockOpname;
use Illuminate\Support\Collection;

interface StockOpnameRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): StockOpname;
    public function create(array $data): StockOpname;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getWithDetails(int $id): StockOpname;
    public function getByStatus(string $status): Collection;
    public function generateOpnameNumber(): string;
}
