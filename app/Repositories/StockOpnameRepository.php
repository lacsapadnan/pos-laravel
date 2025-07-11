<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Repositories\Interface\StockOpnameRepositoryInterface;
use Illuminate\Support\Collection;

final class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    public function all(): Collection
    {
        return StockOpname::with(['creator', 'completedBy'])->orderBy('created_at', 'desc')->get();
    }

    public function findById(int $id): StockOpname
    {
        return StockOpname::with(['creator', 'completedBy'])->findOrFail($id);
    }

    public function create(array $data): StockOpname
    {
        return StockOpname::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $stockOpname = $this->findById($id);
        return $stockOpname->update($data);
    }

    public function delete(int $id): bool
    {
        $stockOpname = $this->findById($id);
        return $stockOpname->delete();
    }

    public function getWithDetails(int $id): StockOpname
    {
        return StockOpname::with([
            'creator',
            'completedBy',
            'details.product.category',
            'details.product.unit',
            'details.countedBy'
        ])->findOrFail($id);
    }

    public function getByStatus(string $status): Collection
    {
        return StockOpname::with(['creator', 'completedBy'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function generateOpnameNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOpname = StockOpname::where('opname_number', 'like', "SO{$date}%")
            ->orderBy('opname_number', 'desc')
            ->first();

        if ($lastOpname) {
            $lastNumber = intval(substr($lastOpname->opname_number, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "SO{$date}" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
