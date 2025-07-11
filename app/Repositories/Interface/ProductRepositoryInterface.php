<?php

namespace App\Repositories\Interface;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function all(): \Illuminate\Support\Collection;
    public function findById(int $id): Product;
    public function create(array $data): Product;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getAllWithRelations(): \Illuminate\Support\Collection;
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;
}
