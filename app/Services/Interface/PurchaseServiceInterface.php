<?php

declare(strict_types=1);

namespace App\Services\Interface;

use App\Models\Purchase;

interface PurchaseServiceInterface
{
    public function getAll(): iterable;
    public function getById(int $id): ?Purchase;
    public function create(array $data, array $items): Purchase;
    public function update(int $id, array $data, array $items): bool;
    public function delete(int $id): bool;
}
