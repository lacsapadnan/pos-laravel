<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\UnitRepositoryInterface;
use App\Services\Interface\ProductServiceInterface;

final class ProductService implements ProductServiceInterface
{
    protected ProductRepositoryInterface $productRepository;
    protected CategoryRepositoryInterface $categoryRepository;
    protected UnitRepositoryInterface $unitRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        UnitRepositoryInterface $unitRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->unitRepository = $unitRepository;
    }

    public function getAllProducts(): \Illuminate\Support\Collection
    {
        return $this->productRepository->all();
    }

    public function getAllProductsWithRelations(): \Illuminate\Support\Collection
    {
        return $this->productRepository->getAllWithRelations();
    }

    public function getProductById(int $id): Product
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    public function updateProduct(int $id, array $data): bool
    {
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }

    public function getAllCategories(): \Illuminate\Support\Collection
    {
        return $this->categoryRepository->all();
    }

    public function getAllUnits(): \Illuminate\Support\Collection
    {
        return $this->unitRepository->all();
    }
}
