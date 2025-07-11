<?php

namespace App\Services\Interface;

use App\Models\Unit;

interface UnitServiceInterface
{
    public function getAllUnits(): \Illuminate\Support\Collection;
    public function getUnitById(int $id): Unit;
    public function createUnit(array $data): Unit;
    public function updateUnit(int $id, array $data): bool;
    public function deleteUnit(int $id): bool;
}
