@extends('layouts.master')

@section('title', 'Point of Sale')

@section('content')
<div class="row">
    <!-- Product Selection Area -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Pilih Produk</h3>
                <div class="gap-2 d-flex">
                    <input type="text" class="form-control" id="productSearch" placeholder="Cari produk..."
                        style="width: 300px;">
                    <button class="btn btn-outline-secondary" onclick="clearSearch()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="productGrid">
                    @forelse($products as $product)
                    <div class="mb-3 col-md-4 col-sm-6 product-item" data-name="{{ strtolower($product->name) }}"
                        data-sku="{{ strtolower($product->sku ?? '') }}">
                        <div class="card product-card h-100" onclick="addToCart({{ $product->id }})">
                            <div class="text-center card-body">
                                <div class="mb-2">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px; margin: 0 auto;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <path d="M21 15l-5-5L5 21" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <h6 class="mb-1 card-title">{{ $product->name }}</h6>
                                <p class="mb-1 text-muted small">{{ $product->sku }}</p>
                                <p class="mb-1 text-success fw-bold">Rp {{ number_format($product->price, 0,
                                    ',', '.') }}</p>
                                <p class="mb-0 text-muted small">Stok: {{ $product->stock }}</p>
                                @if($product->stock <= 0) <span class="mt-1 badge bg-danger">Habis</span>
                                    @elseif($product->stock <= 5) <span class="mt-1 badge bg-warning">Terbatas</span>
                                        @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-4 text-center col-12">
                        <p class="text-muted">Tidak ada produk tersedia</p>
                    </div>
                    @endforelse
                </div>
                <!-- Enhanced pagination container -->
                <div class="pagination-container">
                    {{ $products->onEachSide(1)->links('vendor.pagination.clean') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Cart and Checkout Area -->
    <div class="col-lg-4">
        <div class="card sticky-top">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Keranjang Belanja</h3>
                <button class="btn btn-outline-danger btn-sm d-none" onclick="clearCart()" id="clearCartBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18l-2 13H5L3 6z" />
                        <path d="M8 9v6" />
                        <path d="M16 9v6" />
                        <path d="M9 1v2h6V1" />
                    </svg>
                    Kosongkan
                </button>
            </div>
            <div class="p-0 card-body">
                <!-- Cart Items -->
                <div id="cartItems" class="p-3">
                    <!-- Empty Cart Message -->
                    <div id="emptyCart" class="py-4 text-center text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" class="mb-2">
                            <path d="M3 3h18l-1 13H4L3 3z" />
                            <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                            <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                        </svg>
                        <p>Keranjang kosong</p>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div id="cartSummary" class="p-3 border-top d-none">
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span id="subtotalAmount">Rp 0</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Pajak:</span>
                        <span id="taxAmount">Rp 0</span>
                    </div>
                    <hr>
                    <div class="mb-3 d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="totalAmount">Rp 0</span>
                    </div>

                    <!-- Customer Selection -->
                    <div class="mb-3">
                        <label class="form-label">Konsumen (Opsional)</label>
                        <select class="form-select" id="customerId">
                            <option value="">Konsumen Umum</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="paymentMethod" required>
                            <option value="cash">Tunai</option>
                            <option value="card">Kartu</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <!-- Payment Amount -->
                    <div class="mb-3">
                        <label class="form-label">Jumlah Bayar</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="paidAmount" placeholder="0" min="0"
                                step="100">
                        </div>
                        <div class="form-text" id="changeAmount"></div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="notes" rows="2"
                            placeholder="Catatan transaksi..."></textarea>
                    </div>

                    <!-- Checkout Button -->
                    <button class="btn btn-success w-100" onclick="processCheckout()" id="checkoutBtn">
                        Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-style')
<style>
    .product-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #00A63E;
    }

    .cart-item {
        border-bottom: 1px solid #e9ecef;
        padding: 10px 0;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quantity-btn {
        width: 24px;
        height: 24px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
    }

    .quantity-btn:hover {
        background: #f8f9fa;
    }

    .sticky-top {
        top: 20px;
    }

    /* Clean Modern Pagination Styles */
    .pagination-container {
        padding: 0.5rem 0 0 0;
        background: none;
        border-radius: 0;
        box-shadow: none;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .custom-pagination .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .custom-pagination .page-item {
        margin: 0;
    }

    .custom-pagination .page-link {
        min-width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        background: #f8fafc;
        color: #00A63E;
        font-weight: 500;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: none;
        margin: 0 2px;
    }

    .custom-pagination .page-link:hover,
    .custom-pagination .page-link:focus {
        background: #e6f9ee;
        color: #009c3b;
        text-decoration: none;
    }

    .custom-pagination .page-item.active .page-link {
        background: #00A63E;
        color: #fff;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0, 166, 62, 0.10);
    }

    .custom-pagination .page-item.disabled .page-link {
        background: #f1f5f9;
        color: #b0b0b0;
        cursor: not-allowed;
    }

    .custom-pagination .page-link span[aria-hidden="true"] {
        font-size: 1.2rem;
    }

    .custom-pagination .pagination-info {
        margin-top: 0.5rem;
        color: #6b7280;
        font-size: 0.95rem;
        text-align: center;
    }
</style>
@endpush

@push('addon-script')
<script>
    // Initialize cart data structure
    let cartData = {!! json_encode($cartData ?? ['cart' => [], 'subtotal' => 0, 'tax_amount' => 0, 'total_amount' => 0, 'total_items' => 0]) !!};

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize cart
        initializeCart();

        // Initialize event listeners
        initializeEventListeners();
    });

    // Initialize event listeners
    function initializeEventListeners() {
        // Payment amount listener
        const paidAmount = document.getElementById('paidAmount');
        if (paidAmount) {
            paidAmount.addEventListener('input', updateChangeAmount);
        }

        // Product search listener
        const productSearch = document.getElementById('productSearch');
        if (productSearch) {
            productSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.product-item').forEach(item => {
                    const name = item.dataset.name || '';
                    const sku = item.dataset.sku || '';
                    item.style.display = (name.includes(searchTerm) || sku.includes(searchTerm)) ? 'block' : 'none';
                });
            });
        }
    }

    // Update cart display
    function updateCartDisplay() {
        const elements = {
            cartItems: document.getElementById('cartItems'),
            cartSummary: document.getElementById('cartSummary'),
            emptyCart: document.getElementById('emptyCart'),
            clearCartBtn: document.getElementById('clearCartBtn'),
            subtotalAmount: document.getElementById('subtotalAmount'),
            taxAmount: document.getElementById('taxAmount'),
            totalAmount: document.getElementById('totalAmount')
        };

        // Create empty cart message if it doesn't exist
        if (!elements.emptyCart) {
            const emptyCartHTML = `
                <div id="emptyCart" class="py-4 text-center text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" class="mb-2">
                        <path d="M3 3h18l-1 13H4L3 3z" />
                        <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                        <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                    </svg>
                    <p>Keranjang kosong</p>
                </div>
            `;
            elements.cartItems.innerHTML = emptyCartHTML;
            elements.emptyCart = document.getElementById('emptyCart');
        }

        // Check if critical elements exist
        const criticalElements = ['cartItems', 'cartSummary', 'clearCartBtn', 'subtotalAmount', 'taxAmount', 'totalAmount'];
        const missingElements = criticalElements.filter(key => !elements[key]);

        if (missingElements.length > 0) {
            return;
        }

        // Get cart data
        const cart = cartData?.cart || {};
        const hasItems = Object.keys(cart).length > 0;

        // Toggle visibility
        elements.emptyCart.classList.toggle('d-none', hasItems);
        elements.cartSummary.classList.toggle('d-none', !hasItems);
        elements.clearCartBtn.classList.toggle('d-none', !hasItems);

        // Update cart items
        if (!hasItems) {
            elements.cartItems.innerHTML = elements.emptyCart.outerHTML;
            return;
        }

        // Build cart items HTML
        const cartHTML = Object.values(cart)
            .filter(item => item?.product_id)
            .map(item => `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.product_name || 'Unnamed Product'}</h6>
                            <p class="mb-1 text-muted small">${item.product_sku || ''}</p>
                            <p class="mb-0 text-success small">Rp ${formatNumber(item.price || 0)}</p>
                        </div>
                        <div class="text-end">
                            <div class="mb-1 quantity-controls">
                                <button class="quantity-btn" onclick="updateCartItem(${item.product_id}, ${(item.quantity || 0) - 1})">-</button>
                                <span class="px-2">${item.quantity || 0}</span>
                                <button class="quantity-btn" onclick="updateCartItem(${item.product_id}, ${(item.quantity || 0) + 1})">+</button>
                                <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(${item.product_id})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 6L6 18M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="fw-bold">Rp ${formatNumber(item.total_price || 0)}</div>
                        </div>
                    </div>
                </div>
            `).join('');

        elements.cartItems.innerHTML = cartHTML;

        // Update summary amounts
        elements.subtotalAmount.textContent = `Rp ${formatNumber(cartData.subtotal || 0)}`;
        elements.taxAmount.textContent = `Rp ${formatNumber(cartData.tax_amount || 0)}`;
        elements.totalAmount.textContent = `Rp ${formatNumber(cartData.total_amount || 0)}`;

        // Update change amount
        updateChangeAmount();
    }

    // Cart initialization
    function initializeCart() {
        fetch('{{ route("sales.cart.get") }}')
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    cartData = data.cart || { cart: {}, subtotal: 0, tax_amount: 0, total_amount: 0, total_items: 0 };
                    updateCartDisplay();
                } else {
                    throw new Error(data.message || 'Failed to load cart data');
                }
            })
            .catch(error => {
                showAlert('error', 'Terjadi kesalahan saat memuat keranjang');
            });
    }

    // Cart management functions
    function addToCart(productId, quantity = 1) {
        if (!productId) return;

        fetch('{{ route("sales.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                cartData = data.cart || { cart: {}, subtotal: 0, tax_amount: 0, total_amount: 0, total_items: 0 };
                updateCartDisplay();
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Terjadi kesalahan saat menambahkan produk');
        });
    }

    function updateCartItem(productId, quantity) {
        if (quantity <= 0) {
            removeFromCart(productId);
            return;
        }

        fetch('{{ route("sales.cart.update") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cartData = data.cart || { cart: {}, subtotal: 0, tax_amount: 0, total_amount: 0, total_items: 0 };
                updateCartDisplay();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Terjadi kesalahan saat memperbarui keranjang');
        });
    }

    function removeFromCart(productId) {
        fetch('{{ route("sales.cart.remove") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cartData = data.cart || { cart: {}, subtotal: 0, tax_amount: 0, total_amount: 0, total_items: 0 };
                updateCartDisplay();
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Terjadi kesalahan saat menghapus produk');
        });
    }

    function clearCart() {
        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
            fetch('{{ route("sales.cart.clear") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartData = {
                        cart: {},
                        subtotal: 0,
                        tax_amount: 0,
                        total_amount: 0,
                        total_items: 0
                    };
                    updateCartDisplay();
                    showAlert('success', data.message);
                }
            });
        }
    }

    // Payment calculation
    function updateChangeAmount() {
        const totalAmount = parseFloat(document.getElementById('totalAmount').textContent.replace(/[^\d]/g, '')) || 0;
        const paidAmount = parseFloat(document.getElementById('paidAmount').value) || 0;
        const changeAmount = paidAmount - totalAmount;

        const changeElement = document.getElementById('changeAmount');
        if (paidAmount > 0) {
            if (changeAmount >= 0) {
                changeElement.textContent = `Kembalian: Rp ${formatNumber(changeAmount)}`;
                changeElement.className = 'form-text text-success';
            } else {
                changeElement.textContent = `Kurang: Rp ${formatNumber(Math.abs(changeAmount))}`;
                changeElement.className = 'form-text text-danger';
            }
        } else {
            changeElement.textContent = '';
        }
    }

    function processCheckout() {
        const customerId = document.getElementById('customerId').value || null;
        const paymentMethod = document.getElementById('paymentMethod').value;
        const paidAmount = parseFloat(document.getElementById('paidAmount').value);
        const notes = document.getElementById('notes').value;
        const totalAmount = parseFloat(document.getElementById('totalAmount').textContent.replace(/[^\d]/g, ''));

        if (!paidAmount || paidAmount < totalAmount) {
            showAlert('error', 'Jumlah pembayaran tidak mencukupi');
            return;
        }

        const checkoutBtn = document.getElementById('checkoutBtn');
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        fetch('{{ route("sales.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_id: customerId,
                payment_method: paymentMethod,
                paid_amount: paidAmount,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                // Redirect to receipt page
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                showAlert('error', data.message);
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = 'Proses Pembayaran';
            }
        })
        .catch(error => {
            showAlert('error', 'Terjadi kesalahan saat memproses pembayaran');
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = 'Proses Pembayaran';
        });
    }

    // Utility functions
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(parseFloat(number) || 0);
    }

    // Enhanced alert function
    function showAlert(type, message) {
        const alertContainer = document.createElement('div');
        alertContainer.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertContainer.style.zIndex = '9999';
        alertContainer.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertContainer);

        setTimeout(() => {
            alertContainer.remove();
        }, 3000);
    }
</script>
@endpush
