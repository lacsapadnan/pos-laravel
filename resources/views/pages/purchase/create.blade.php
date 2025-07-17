@extends('layouts.master')

@section('title', 'Tambah Pembelian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Pembelian Baru</h3>
    </div>
    <form method="POST" action="{{ route('purchases.store') }}">
        @csrf
        <div class="card-body">
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id"
                        name="supplier_id" required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id')==$supplier->id ? 'selected' : '' }}>{{
                            $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date"
                        value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Keterangan</label>
                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="2"
                    placeholder="Keterangan (opsional)">{{ old('note') }}</textarea>
                @error('note')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 table-responsive">
                <table class="table table-bordered" id="items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $oldItems = old('items', [[]]); @endphp
                        @foreach($oldItems as $i => $item)
                        <tr>
                            <td>
                                <select name="items[{{ $i }}][product_id]" class="form-select" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ (isset($item['product_id']) &&
                                        $item['product_id']==$product->id) ? 'selected' : '' }}>{{ $product->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $i }}][quantity]" class="form-control"
                                    value="{{ $item['quantity'] ?? 1 }}" min="1" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $i }}][price]" class="form-control"
                                    value="{{ $item['price'] ?? 0 }}" min="0" step="0.01" required>
                            </td>
                            <td class="item-subtotal">0</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="mt-3 btn btn-success btn-sm" id="add-row">Tambah Produk</button>
            </div>
            <div class="mb-3 text-end">
                <strong>Total: Rp <span id="total-amount">0</span></strong>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@push('addon-style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        background-color: #fff;
        font-size: 1rem;
        line-height: 1.5;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #495057;
        line-height: 24px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 10px;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #206bc4;
        box-shadow: 0 0 0 0.2rem rgba(32, 107, 196, 0.25);
    }
</style>
@endpush

@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const products = @json($products);
    function recalcSubtotal(row) {
        const qty = parseFloat(row.find('input[name$="[quantity]"]').val()) || 0;
        const price = parseFloat(row.find('input[name$="[price]"]').val()) || 0;
        row.find('.item-subtotal').text((qty * price).toLocaleString('id-ID'));
    }
    function recalcTotal() {
        let total = 0;
        $('#items-table tbody tr').each(function() {
            const qty = parseFloat($(this).find('input[name$="[quantity]"]').val()) || 0;
            const price = parseFloat($(this).find('input[name$="[price]"]').val()) || 0;
            total += qty * price;
        });
        $('#total-amount').text(total.toLocaleString('id-ID'));
    }
    function initSelect2() {
        $('#items-table select[name$="[product_id]"]').select2({
            theme: 'default',
            width: '100%',
            dropdownParent: $('#items-table')
        });
    }
    $(document).on('input', 'input[name$="[quantity]"]', function() {
        const row = $(this).closest('tr');
        recalcSubtotal(row);
        recalcTotal();
    });
    $(document).on('input', 'input[name$="[price]"]', function() {
        const row = $(this).closest('tr');
        recalcSubtotal(row);
        recalcTotal();
    });
    $('#add-row').on('click', function() {
        const idx = $('#items-table tbody tr').length;
        let options = '<option value="">Pilih Produk</option>';
        products.forEach(product => {
            options += `<option value="${product.id}">${product.name}</option>`;
        });
        const row = `<tr>
            <td><select name="items[${idx}][product_id]" class="form-select" required>${options}</select></td>
            <td><input type="number" name="items[${idx}][quantity]" class="form-control" value="1" min="1" required></td>
            <td><input type="number" name="items[${idx}][price]" class="form-control" value="0" min="0" step="0.01" required></td>
            <td class="item-subtotal">0</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
        </tr>`;
        $('#items-table tbody').append(row);
        initSelect2();
    });
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        recalcTotal();
    });
    $(document).ready(function() {
        $('#items-table tbody tr').each(function() {
            recalcSubtotal($(this));
        });
        recalcTotal();
        initSelect2();
    });
</script>
@endpush