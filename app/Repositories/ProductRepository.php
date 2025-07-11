<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interface\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ProductRepository implements ProductRepositoryInterface
{
    public function all(): \Illuminate\Support\Collection
    {
        return Product::all();
    }

    public function getAllWithRelations(): \Illuminate\Support\Collection
    {
        return Product::with(['category', 'unit'])->get();
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with(['category', 'unit'])->paginate($perPage);
    }

    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->findById($id);
        return $product->update($data);
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);
        return $product->delete();
    }
}
