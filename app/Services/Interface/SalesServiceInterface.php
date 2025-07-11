<?php

declare(strict_types=1);

namespace App\Services\Interface;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SalesServiceInterface
{
    public function getAllSales(): Collection;

    public function getSalesPaginated(int $perPage = 15): LengthAwarePaginator;

    public function getSaleById(int $id): ?Sale;

    public function getSaleByTransactionId(string $transactionId): ?Sale;

    public function addToCart(int $productId, int $quantity): array;

    public function updateCartItem(int $productId, int $quantity): array;

    public function removeFromCart(int $productId): array;

    public function getCart(): array;

    public function clearCart(): void;

    public function calculateCartTotals(): array;

    public function processCheckout(array $checkoutData): Sale;

    public function cancelSale(int $saleId): bool;

    public function getTodaySales(): Collection;

    public function getDailySalesTotal(): float;

    public function getSalesReport(string $startDate, string $endDate): array;
}
