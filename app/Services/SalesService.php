<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\SalesRepositoryInterface;
use App\Services\Interface\SalesServiceInterface;
use App\Services\Interface\StockServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

final readonly class SalesService implements SalesServiceInterface
{
    public function __construct(
        private SalesRepositoryInterface $salesRepository,
        private ProductRepositoryInterface $productRepository,
        private StockServiceInterface $stockService
    ) {}

    public function getAllSales(): Collection
    {
        return $this->salesRepository->getAllSales();
    }

    public function getSalesPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->salesRepository->getSalesPaginated($perPage);
    }

    public function getSaleById(int $id): ?Sale
    {
        return $this->salesRepository->getSaleById($id);
    }

    public function getSaleByTransactionId(string $transactionId): ?Sale
    {
        return $this->salesRepository->getSaleByTransactionId($transactionId);
    }

    public function addToCart(int $productId, int $quantity): array
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \Exception('Produk tidak ditemukan.');
        }

        if ($product->stock < $quantity) {
            throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($product->stock < $newQuantity) {
                throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
            }
            $cart[$productId]['quantity'] = $newQuantity;
            $cart[$productId]['total_price'] = $newQuantity * $product->price;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'price' => $product->price,
                'quantity' => $quantity,
                'total_price' => $quantity * $product->price,
                'stock_available' => $product->stock,
            ];
        }

        Session::put('sales_cart', $cart);

        return $this->calculateCartTotals();
    }

    public function updateCartItem(int $productId, int $quantity): array
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($productId);
        }

        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \Exception('Produk tidak ditemukan.');
        }

        if ($product->stock < $quantity) {
            throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            $cart[$productId]['total_price'] = $quantity * $product->price;

            Session::put('sales_cart', $cart);
        }

        return $this->calculateCartTotals();
    }

    public function removeFromCart(int $productId): array
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        Session::put('sales_cart', $cart);

        return $this->calculateCartTotals();
    }

    public function getCart(): array
    {
        return Session::get('sales_cart', []);
    }

    public function clearCart(): void
    {
        Session::forget('sales_cart');
    }

    public function calculateCartTotals(): array
    {
        $cart = $this->getCart();
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $subtotal += $item['total_price'];
            $totalItems += $item['quantity'];
        }

        $taxRate = 0; // Set tax rate as needed
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        return [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $total,
            'total_items' => $totalItems,
        ];
    }

    public function processCheckout(array $checkoutData): Sale
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            throw new \Exception('Keranjang belanja kosong.');
        }

        return DB::transaction(function () use ($checkoutData, $cart) {
            $cartTotals = $this->calculateCartTotals();

            // Validate payment
            $paidAmount = $checkoutData['paid_amount'] ?? 0;
            if ($paidAmount < $cartTotals['total_amount']) {
                throw new \Exception('Jumlah pembayaran tidak mencukupi.');
            }

            // Create sale record
            $saleData = [
                'customer_id' => $checkoutData['customer_id'] ?? null,
                'user_id' => Auth::id(),
                'subtotal' => $cartTotals['subtotal'],
                'tax_amount' => $cartTotals['tax_amount'],
                'discount_amount' => $checkoutData['discount_amount'] ?? 0,
                'total_amount' => $cartTotals['total_amount'],
                'paid_amount' => $paidAmount,
                'change_amount' => $paidAmount - $cartTotals['total_amount'],
                'payment_method' => $checkoutData['payment_method'] ?? 'cash',
                'status' => 'completed',
                'notes' => $checkoutData['notes'] ?? null,
            ];

            $sale = $this->salesRepository->createSale($saleData);

            // Create sale items and reduce stock
            foreach ($cart as $item) {
                // Create sales item
                SalesItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_sku' => $item['product_sku'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total_price'],
                ]);

                // Reduce stock
                $this->stockService->createStockOut([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'type' => 'sale',
                    'notes' => 'Penjualan - ' . $sale->transaction_id,
                ]);
            }

            // Clear cart after successful checkout
            $this->clearCart();

            return $sale->load(['customer', 'user', 'salesItems.product']);
        });
    }

    public function cancelSale(int $saleId): bool
    {
        $sale = $this->getSaleById($saleId);

        if (!$sale) {
            throw new \Exception('Transaksi penjualan tidak ditemukan.');
        }

        if ($sale->status === 'cancelled') {
            throw new \Exception('Transaksi sudah dibatalkan.');
        }

        return DB::transaction(function () use ($sale) {
            // Restore stock for each item
            foreach ($sale->salesItems as $item) {
                $this->stockService->createStockIn([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'type' => 'return',
                    'notes' => 'Pembatalan penjualan - ' . $sale->transaction_id,
                ]);
            }

            // Update sale status
            return $this->salesRepository->updateSale($sale->id, ['status' => 'cancelled']);
        });
    }

    public function getTodaySales(): Collection
    {
        return $this->salesRepository->getTodaySales();
    }

    public function getDailySalesTotal(): float
    {
        $todaySales = $this->getTodaySales();
        return $todaySales->where('status', 'completed')->sum('total_amount');
    }

    public function getSalesReport(string $startDate, string $endDate): array
    {
        $sales = $this->salesRepository->getSalesByDateRange($startDate, $endDate);
        $completedSales = $sales->where('status', 'completed');

        return [
            'total_sales' => $completedSales->count(),
            'total_revenue' => $completedSales->sum('total_amount'),
            'total_items_sold' => $completedSales->sum(function ($sale) {
                return $sale->salesItems->sum('quantity');
            }),
            'average_sale_amount' => $completedSales->avg('total_amount') ?? 0,
            'sales_by_payment_method' => $completedSales->groupBy('payment_method')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount'),
                ];
            }),
            'daily_breakdown' => $completedSales->groupBy(function ($sale) {
                return $sale->created_at->format('Y-m-d');
            })->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount'),
                ];
            }),
        ];
    }
}
