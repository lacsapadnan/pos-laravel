<?php

namespace App\Services;

use App\Models\Unit;
use App\Repositories\Interface\UnitRepositoryInterface;

final class UnitService
{
    protected UnitRepositoryInterface $unitRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(UnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    public function getAllUnits(): \Illuminate\Support\Collection
    {
        return $this->unitRepository->all();
    }

    public function getUnitById(int $id): Unit
    {
        return $this->unitRepository->findById($id);
    }

    public function createUnit(array $data): Unit
    {
        return $this->unitRepository->create($data);
    }

    public function updateUnit(int $id, array $data): bool
    {
        return $this->unitRepository->update($id, $data);
    }

    public function deleteUnit(int $id): bool
    {
        return $this->unitRepository->delete($id);
    }
}
