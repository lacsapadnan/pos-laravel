<?php

declare(strict_types=1);

namespace App\Repositories\Interface;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SalesRepositoryInterface
{
    public function getAllSales(): Collection;

    public function getSalesPaginated(int $perPage = 15): LengthAwarePaginator;

    public function getSaleById(int $id): ?Sale;

    public function getSaleByTransactionId(string $transactionId): ?Sale;

    public function createSale(array $data): Sale;

    public function updateSale(int $id, array $data): bool;

    public function deleteSale(int $id): bool;

    public function getSalesByDateRange(string $startDate, string $endDate): Collection;

    public function getSalesByUser(int $userId): Collection;

    public function getSalesByCustomer(int $customerId): Collection;

    public function getTodaySales(): Collection;
}
