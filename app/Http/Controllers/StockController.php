<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Interface\StockServiceInterface;
use App\Services\Interface\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class StockController extends Controller
{
    protected StockServiceInterface $stockService;
    protected ProductServiceInterface $productService;

    public function __construct(
        StockServiceInterface $stockService,
        ProductServiceInterface $productService
    ) {
        $this->stockService = $stockService;
        $this->productService = $productService;
    }

    /**
     * Display the stock management dashboard
     */
    public function index()
    {
        $stockSummary = $this->stockService->getStockSummary();
        $movementStats = $this->stockService->getMovementStatistics();
        $recentMovements = $this->stockService->getRecentMovements(10);
        $lowStockProducts = $this->stockService->getLowStockProducts();

        return view('pages.stock.index', compact(
            'stockSummary',
            'movementStats',
            'recentMovements',
            'lowStockProducts'
        ));
    }

    /**
     * Display stock movements
     */
    public function movements()
    {
        $movements = $this->stockService->getAllMovements();
        return view('pages.stock.movements', compact('movements'));
    }

    /**
     * Show form for stock in
     */
    public function stockInForm()
    {
        $products = $this->productService->getAllProducts();
        return view('pages.stock.stock-in', compact('products'));
    }

    /**
     * Process stock in
     */
    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ], [
            'product_id.required' => 'Produk harus dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'quantity.required' => 'Jumlah harus diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
            'unit_cost.numeric' => 'Harga satuan harus berupa angka.',
            'unit_cost.min' => 'Harga satuan tidak boleh negatif.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $this->stockService->createStockIn([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_cost' => $request->unit_cost,
                'notes' => $request->notes,
                'reference_type' => 'manual',
            ]);

            DB::commit();
            return redirect()->route('stock.index')->withSuccess('Stok masuk berhasil dicatat');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors("Gagal mencatat stok masuk: " . $th->getMessage())->withInput();
        }
    }

    /**
     * Show form for stock out
     */
    public function stockOutForm()
    {
        $products = $this->productService->getAllProducts();
        return view('pages.stock.stock-out', compact('products'));
    }

    /**
     * Process stock out
     */
    public function stockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ], [
            'product_id.required' => 'Produk harus dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'quantity.required' => 'Jumlah harus diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $this->stockService->createStockOut([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'reference_type' => 'manual',
            ]);

            DB::commit();
            return redirect()->route('stock.index')->withSuccess('Stok keluar berhasil dicatat');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors("Gagal mencatat stok keluar: " . $th->getMessage())->withInput();
        }
    }

    /**
     * Show form for stock adjustment
     */
    public function adjustmentForm()
    {
        $products = $this->productService->getAllProductsWithRelations();
        return view('pages.stock.adjustment', compact('products'));
    }

    /**
     * Process stock adjustment
     */
    public function adjustment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_stock' => 'required|integer|min:0',
            'notes' => 'required|string|max:500',
        ], [
            'product_id.required' => 'Produk harus dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'new_stock.required' => 'Stok baru harus diisi.',
            'new_stock.integer' => 'Stok baru harus berupa angka.',
            'new_stock.min' => 'Stok baru tidak boleh negatif.',
            'notes.required' => 'Alasan penyesuaian harus diisi.',
            'notes.max' => 'Alasan maksimal 500 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $this->stockService->createStockAdjustment([
                'product_id' => $request->product_id,
                'new_stock' => $request->new_stock,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('stock.index')->withSuccess('Penyesuaian stok berhasil dicatat');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors("Gagal melakukan penyesuaian stok: " . $th->getMessage())->withInput();
        }
    }
}
