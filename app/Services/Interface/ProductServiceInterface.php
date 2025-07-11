<?php

namespace App\Services\Interface;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    public function getAllProducts(): \Illuminate\Support\Collection;
    public function getProductById(int $id): Product;
    public function createProduct(array $data): Product;
    public function updateProduct(int $id, array $data): bool;
    public function deleteProduct(int $id): bool;
    public function getAllProductsWithRelations(): \Illuminate\Support\Collection;
    public function getAllCategories(): \Illuminate\Support\Collection;
    public function getAllUnits(): \Illuminate\Support\Collection;
    public function getAllProductsPaginated(int $perPage = 15): LengthAwarePaginator;
}
