<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Repositories\Interface\UnitRepositoryInterface;

final class UnitRepository implements UnitRepositoryInterface
{
    public function all(): \Illuminate\Support\Collection
    {
        return Unit::all();
    }

    public function findById(int $id): Unit
    {
        return Unit::findOrFail($id);
    }

    public function create(array $data): Unit
    {
        return Unit::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $unit = $this->findById($id);
        return $unit->update($data);
    }

    public function delete(int $id): bool
    {
        $unit = $this->findById($id);
        return $unit->delete();
    }
}
