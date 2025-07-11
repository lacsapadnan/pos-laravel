<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Interface\CustomerServiceInterface;
use App\Services\Interface\ProductServiceInterface;
use App\Services\Interface\SettingServiceInterface;
use App\Services\Interface\SalesServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SalesController extends Controller
{
    public function __construct(
        private readonly SalesServiceInterface $salesService,
        private readonly ProductServiceInterface $productService,
        private readonly CustomerServiceInterface $customerService,
        private readonly SettingServiceInterface $settingService
    ) {}

    /**
     * Display the POS interface
     */
    public function index(): View
    {
        $products = $this->productService->getAllProductsPaginated(9);
        $customers = $this->customerService->getAllCustomers();
        $cartData = $this->salesService->calculateCartTotals();

        return view('pages.sales.pos', compact('products', 'customers', 'cartData'));
    }

    /**
     * Display sales history
     */
    public function history(): View
    {
        $sales = $this->salesService->getSalesPaginated(20);

        return view('pages.sales.history', compact('sales'));
    }

    /**
     * Show specific sale details
     */
    public function show(int $id): View
    {
        $sale = $this->salesService->getSaleById($id);

        if (!$sale) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        return view('pages.sales.show', compact('sale'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request): JsonResponse
    {
        try {
            $productId = (int) $request->input('product_id');
            $quantity = (int) $request->input('quantity', 1);

            $cartData = $this->salesService->addToCart($productId, $quantity);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request): JsonResponse
    {
        try {
            $productId = (int) $request->input('product_id');
            $quantity = (int) $request->input('quantity');

            $cartData = $this->salesService->updateCartItem($productId, $quantity);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui',
                'cart' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        try {
            $productId = (int) $request->input('product_id');

            $cartData = $this->salesService->removeFromCart($productId);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'cart' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get current cart data
     */
    public function getCart(): JsonResponse
    {
        $cartData = $this->salesService->calculateCartTotals();

        return response()->json([
            'success' => true,
            'cart' => $cartData
        ]);
    }

    /**
     * Clear cart
     */
    public function clearCart(): JsonResponse
    {
        $this->salesService->clearCart();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan'
        ]);
    }

    /**
     * Process checkout
     */
    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|in:cash,card,transfer,qris',
            'paid_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $sale = $this->salesService->processCheckout($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses',
                'sale' => $sale,
                'transaction_id' => $sale->transaction_id,
                'redirect_url' => route('sales.receipt', $sale->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display receipt
     */
    public function receipt(int $id): View
    {
        $sale = $this->salesService->getSaleById($id);

        if (!$sale) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $websiteSetting = $this->settingService->getSettings();

        return view('pages.sales.receipt', compact('sale', 'websiteSetting'));
    }

    /**
     * Print receipt
     */
    public function printReceipt(int $id): View
    {
        $sale = $this->salesService->getSaleById($id);

        if (!$sale) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        return view('pages.sales.print-receipt', compact('sale'));
    }

    /**
     * Cancel sale
     */
    public function cancel(int $id): RedirectResponse
    {
        try {
            $this->salesService->cancelSale($id);

            return redirect()
                ->route('sales.history')
                ->with('success', 'Transaksi berhasil dibatalkan dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get today's sales summary
     */
    public function todaySummary(): JsonResponse
    {
        $todaySales = $this->salesService->getTodaySales();
        $totalRevenue = $this->salesService->getDailySalesTotal();

        return response()->json([
            'success' => true,
            'total_sales' => $todaySales->count(),
            'total_revenue' => $totalRevenue,
            'completed_sales' => $todaySales->where('status', 'completed')->count(),
            'cancelled_sales' => $todaySales->where('status', 'cancelled')->count(),
        ]);
    }
}
