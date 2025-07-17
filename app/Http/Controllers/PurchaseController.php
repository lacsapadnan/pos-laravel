<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Services\Interface\PurchaseServiceInterface;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseServiceInterface $purchaseService
    ) {}

    public function index(): View
    {
        $purchases = $this->purchaseService->getAll();
        return view('pages.purchase.index', compact('purchases'));
    }

    public function create(): View
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('pages.purchase.create', compact('suppliers', 'products'));
    }

    public function store(StorePurchaseRequest $request): RedirectResponse
    {
        $data = $request->only(['supplier_id', 'date', 'note']);
        $items = $request->input('items');
        $this->purchaseService->create($data, $items);
        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function show(int $id): View
    {
        $purchase = $this->purchaseService->getById($id);
        abort_if(!$purchase, 404);
        return view('pages.purchase.show', compact('purchase'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->purchaseService->delete($id);
        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
