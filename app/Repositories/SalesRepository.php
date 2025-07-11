<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Sale;
use App\Repositories\Interface\SalesRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final readonly class SalesRepository implements SalesRepositoryInterface
{
    public function getAllSales(): Collection
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSalesPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getSaleById(int $id): ?Sale
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->find($id);
    }

    public function getSaleByTransactionId(string $transactionId): ?Sale
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->where('transaction_id', $transactionId)
            ->first();
    }

    public function createSale(array $data): Sale
    {
        return Sale::create($data);
    }

    public function updateSale(int $id, array $data): bool
    {
        $sale = Sale::find($id);
        if (!$sale) {
            return false;
        }
        return $sale->update($data);
    }

    public function deleteSale(int $id): bool
    {
        $sale = Sale::find($id);
        if ($sale) {
            return $sale->delete();
        }
        return false;
    }

    public function getSalesByDateRange(string $startDate, string $endDate): Collection
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSalesByUser(int $userId): Collection
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSalesByCustomer(int $customerId): Collection
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTodaySales(): Collection
    {
        return Sale::with(['customer', 'user', 'salesItems.product'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
