<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\StockOpnameRepositoryInterface;
use App\Services\Interface\StockOpnameServiceInterface;
use App\Services\Interface\StockServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class StockOpnameService implements StockOpnameServiceInterface
{
    protected StockOpnameRepositoryInterface $stockOpnameRepository;
    protected ProductRepositoryInterface $productRepository;
    protected StockServiceInterface $stockService;

    public function __construct(
        StockOpnameRepositoryInterface $stockOpnameRepository,
        ProductRepositoryInterface $productRepository,
        StockServiceInterface $stockService
    ) {
        $this->stockOpnameRepository = $stockOpnameRepository;
        $this->productRepository = $productRepository;
        $this->stockService = $stockService;
    }

    public function getAllOpnames(): Collection
    {
        return $this->stockOpnameRepository->all();
    }

    public function getOpnameById(int $id): StockOpname
    {
        return $this->stockOpnameRepository->findById($id);
    }

    public function createOpname(array $data): StockOpname
    {
        return DB::transaction(function () use ($data) {
            $opnameData = [
                'opname_number' => $this->generateOpnameNumber(),
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'opname_date' => $data['opname_date'],
                'created_by' => Auth::id(),
                'status' => 'draft',
            ];

            $opname = $this->stockOpnameRepository->create($opnameData);

            // Add products to opname if specified
            if (!empty($data['product_ids'])) {
                $this->addProductsToOpname($opname->id, $data['product_ids']);
            }

            return $opname;
        });
    }

    public function updateOpname(int $id, array $data): bool
    {
        $opname = $this->getOpnameById($id);

        if (!in_array($opname->status, ['draft'])) {
            throw new \Exception('Hanya opname dengan status draft yang dapat diubah');
        }

        return $this->stockOpnameRepository->update($id, $data);
    }

    public function deleteOpname(int $id): bool
    {
        $opname = $this->getOpnameById($id);

        if (!in_array($opname->status, ['draft', 'cancelled'])) {
            throw new \Exception('Hanya opname dengan status draft atau cancelled yang dapat dihapus');
        }

        return $this->stockOpnameRepository->delete($id);
    }

    public function startOpname(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $opname = $this->getOpnameById($id);

            if (!$opname->canBeStarted()) {
                throw new \Exception('Opname tidak dapat dimulai');
            }

            // If no products added yet, add all products
            if ($opname->total_products === 0) {
                $allProducts = $this->productRepository->all();
                $this->addProductsToOpname($id, $allProducts->pluck('id')->toArray());
            }

            return $this->stockOpnameRepository->update($id, [
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        });
    }

    public function completeOpname(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $opname = $this->getOpnameWithDetails($id);

            if (!$opname->canBeCompleted()) {
                throw new \Exception('Opname belum dapat diselesaikan. Pastikan semua produk sudah dihitung.');
            }

            // Process stock adjustments
            $adjustments = $this->processOpnameCompletion($id);

            return $this->stockOpnameRepository->update($id, [
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by' => Auth::id(),
            ]);
        });
    }

    public function cancelOpname(int $id): bool
    {
        $opname = $this->getOpnameById($id);

        if (!$opname->canBeCancelled()) {
            throw new \Exception('Opname tidak dapat dibatalkan');
        }

        return $this->stockOpnameRepository->update($id, [
            'status' => 'cancelled',
        ]);
    }

    public function addProductsToOpname(int $opnameId, array $productIds = []): int
    {
        $opname = $this->getOpnameById($opnameId);

        if ($opname->status !== 'draft') {
            throw new \Exception('Produk hanya dapat ditambahkan pada opname dengan status draft');
        }

        // If no specific products, add all products
        if (empty($productIds)) {
            $productIds = $this->productRepository->all()->pluck('id')->toArray();
        }

        $addedCount = 0;

        foreach ($productIds as $productId) {
            $product = $this->productRepository->findById($productId);

            // Check if product already exists in this opname
            $existing = StockOpnameDetail::where('stock_opname_id', $opnameId)
                ->where('product_id', $productId)
                ->first();

            if (!$existing) {
                StockOpnameDetail::create([
                    'stock_opname_id' => $opnameId,
                    'product_id' => $productId,
                    'system_stock' => $product->stock,
                    'unit_cost' => $product->price,
                ]);
                $addedCount++;
            }
        }

        // Update total products count
        $totalProducts = StockOpnameDetail::where('stock_opname_id', $opnameId)->count();
        $this->stockOpnameRepository->update($opnameId, ['total_products' => $totalProducts]);

        return $addedCount;
    }

    public function updateProductCount(int $opnameId, int $productId, int $physicalStock, ?string $notes = null): StockOpnameDetail
    {
        $opname = $this->getOpnameById($opnameId);

        if ($opname->status !== 'in_progress') {
            throw new \Exception('Penghitungan hanya dapat dilakukan pada opname yang sedang berjalan');
        }

        $detail = StockOpnameDetail::where('stock_opname_id', $opnameId)
            ->where('product_id', $productId)
            ->firstOrFail();

        $wasNotCounted = !$detail->is_counted;

        $detail->update([
            'physical_stock' => $physicalStock,
            'notes' => $notes,
            'is_counted' => true,
            'counted_by' => Auth::id(),
            'counted_at' => now(),
        ]);

        $detail->calculateVariance();
        $detail->save();

        // Update opname progress
        if ($wasNotCounted) {
            $countedProducts = StockOpnameDetail::where('stock_opname_id', $opnameId)
                ->where('is_counted', true)
                ->count();

            $totalVarianceValue = StockOpnameDetail::where('stock_opname_id', $opnameId)
                ->sum('variance_value');

            $this->stockOpnameRepository->update($opnameId, [
                'counted_products' => $countedProducts,
                'total_variance_value' => $totalVarianceValue,
            ]);
        }

        return $detail;
    }

    public function generateOpnameNumber(): string
    {
        return $this->stockOpnameRepository->generateOpnameNumber();
    }

    public function getOpnameWithDetails(int $id): StockOpname
    {
        return $this->stockOpnameRepository->getWithDetails($id);
    }

    public function processOpnameCompletion(int $id): array
    {
        $opname = $this->getOpnameWithDetails($id);
        $adjustments = [];

        foreach ($opname->details as $detail) {
            if ($detail->variance !== 0) {
                // Create stock adjustment for each variance
                $movement = $this->stockService->createStockAdjustment([
                    'product_id' => $detail->product_id,
                    'new_stock' => $detail->physical_stock,
                    'notes' => "Stock Opname #{$opname->opname_number}" . ($detail->notes ? " - {$detail->notes}" : ''),
                    'reference_type' => 'opname',
                    'reference_id' => $opname->id,
                ]);

                $adjustments[] = $movement;
            }
        }

        return $adjustments;
    }
}
