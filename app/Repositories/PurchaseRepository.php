<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Purchase;
use App\Repositories\Interface\PurchaseRepositoryInterface;

final class PurchaseRepository implements PurchaseRepositoryInterface
{
    public function all(): iterable
    {
        return Purchase::with(['supplier', 'items'])->latest()->get();
    }

    public function find(int $id): ?Purchase
    {
        return Purchase::find($id);
    }

    public function create(array $data): Purchase
    {
        return Purchase::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $purchase = Purchase::find($id);
        return $purchase ? $purchase->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $purchase = Purchase::find($id);
        return $purchase ? $purchase->delete() : false;
    }

    public function findWithItems(int $id): ?Purchase
    {
        return Purchase::with(['supplier', 'items.product'])->find($id);
    }
}
