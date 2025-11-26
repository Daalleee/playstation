@extends('kasir.layout')

@section('kasir_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-white"><i class="bi bi-plus-circle me-2 text-primary"></i>Buat Transaksi Baru</h4>
        <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('kasir.rentals.store') }}" id="rentalForm">
        @csrf
        
        <div class="row">
            <!-- Left Column: Customer & Dates -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 bg-dark text-white">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Data Pelanggan & Waktu</h6>
                    </div>
                    <div class="card-body">
                        <!-- Customer Selection -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pelanggan</label>
                            <select class="form-select bg-dark text-white border-secondary @error('user_id') is-invalid @enderror" name="user_id" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->phone ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text text-white-50">Pilih pelanggan yang sudah terdaftar.</div>
                        </div>

                        <!-- Start Date -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mulai Sewa</label>
                            <input type="datetime-local" class="form-control bg-dark text-white border-secondary @error('start_at') is-invalid @enderror" 
                                   name="start_at" value="{{ old('start_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('start_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Due Date -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jatuh Tempo (Estimasi)</label>
                            <input type="datetime-local" class="form-control bg-dark text-white border-secondary @error('due_at') is-invalid @enderror" 
                                   name="due_at" value="{{ old('due_at') }}">
                            @error('due_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text text-white-50">Opsional. Kosongkan jika open rental.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Items & Payment -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm mb-4 bg-dark text-white">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center border-bottom border-secondary">
                        <h6 class="mb-0"><i class="bi bi-cart-check me-2"></i>Item Sewa</h6>
                        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Item
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle mb-0" id="itemsTable">
                                <thead class="table-dark border-bottom border-secondary">
                                    <tr>
                                        <th style="width: 20%">Tipe</th>
                                        <th style="width: 35%">Item</th>
                                        <th style="width: 15%">Qty</th>
                                        <th style="width: 25%">Harga (Rp)</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer" class="border-secondary">
                                    <!-- Items will be added here via JS -->
                                </tbody>
                                <tfoot class="table-dark border-top border-secondary">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                        <td colspan="2" class="fw-bold text-warning fs-5" id="subtotalDisplay">Rp 0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if($errors->has('items'))
                            <div class="alert alert-danger m-3">{{ $errors->first('items') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="card border-0 shadow-sm bg-dark text-white">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Diskon (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-secondary text-white border-secondary">Rp</span>
                                    <input type="number" class="form-control bg-dark text-white border-secondary" name="discount" id="discountInput" value="{{ old('discount', 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Bayar Awal / DP (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-secondary text-white border-secondary">Rp</span>
                                    <input type="number" class="form-control bg-dark text-white border-secondary" name="paid" value="{{ old('paid', 0) }}" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 py-3 text-end">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-save me-2"></i>Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Data for JS -->
<script>
    const units = @json($units);
    const games = @json($games);
    const accessories = @json($accessories);
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    let itemCount = 0;

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
    }

    function calculateSubtotal() {
        let total = 0;
        document.querySelectorAll('.item-price').forEach(input => {
            const row = input.closest('tr');
            const qty = row.querySelector('.item-qty').value || 0;
            const price = input.value || 0;
            total += (qty * price);
        });
        document.getElementById('subtotalDisplay').textContent = formatRupiah(total);
    }

    function createItemRow() {
        const index = itemCount++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select class="form-select form-select-sm item-type bg-dark text-white border-secondary" name="items[${index}][type]" required>
                    <option value="unit_ps">Unit PS</option>
                    <option value="game">Game</option>
                    <option value="accessory">Aksesoris</option>
                </select>
            </td>
            <td>
                <select class="form-select form-select-sm item-select bg-dark text-white border-secondary" name="items[${index}][id]" required>
                    <option value="">-- Pilih Item --</option>
                </select>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm item-qty bg-dark text-white border-secondary" name="items[${index}][quantity]" value="1" min="1" required>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-secondary text-white border-secondary">Rp</span>
                    <input type="number" class="form-control item-price bg-dark text-white border-secondary" name="items[${index}][price]" required>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        // Event Listeners
        const typeSelect = tr.querySelector('.item-type');
        const itemSelect = tr.querySelector('.item-select');
        const priceInput = tr.querySelector('.item-price');
        const qtyInput = tr.querySelector('.item-qty');
        const removeBtn = tr.querySelector('.remove-item');

        // Populate items based on type
        function populateItems() {
            const type = typeSelect.value;
            let data = [];
            if (type === 'unit_ps') data = units;
            else if (type === 'game') data = games;
            else if (type === 'accessory') data = accessories;

            itemSelect.innerHTML = '<option value="">-- Pilih Item --</option>';
            data.forEach(item => {
                const name = item.nama || item.judul || item.name;
                const stock = item.stok !== undefined ? item.stok : item.stock;
                const price = item.price_per_hour || item.harga_per_hari || 0; // Simplified logic
                
                // Only show items with stock > 0
                if(stock > 0) {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${name} (Stok: ${stock})`;
                    option.dataset.price = price;
                    itemSelect.appendChild(option);
                }
            });
        }

        // Auto-fill price
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.price) {
                priceInput.value = selectedOption.dataset.price;
                calculateSubtotal();
            }
        });

        typeSelect.addEventListener('change', populateItems);
        
        // Recalculate on changes
        qtyInput.addEventListener('input', calculateSubtotal);
        priceInput.addEventListener('input', calculateSubtotal);

        removeBtn.addEventListener('click', function() {
            tr.remove();
            calculateSubtotal();
        });

        // Initial population
        populateItems();

        container.appendChild(tr);
    }

    addItemBtn.addEventListener('click', createItemRow);

    // Add first item by default
    createItemRow();
});
</script>
@endsection
