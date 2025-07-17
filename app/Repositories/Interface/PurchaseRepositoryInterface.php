<?php

declare(strict_types=1);

namespace App\Repositories\Interface;

use App\Models\Purchase;

interface PurchaseRepositoryInterface
{
    public function all(): iterable;
    public function find(int $id): ?Purchase;
    public function create(array $data): Purchase;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findWithItems(int $id): ?Purchase;
}
