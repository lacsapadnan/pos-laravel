<?php

namespace App\Repositories\Interface;

use App\Models\Unit;

interface UnitRepositoryInterface
{
    public function all(): \Illuminate\Support\Collection;
    public function findById(int $id): Unit;
    public function create(array $data): Unit;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
