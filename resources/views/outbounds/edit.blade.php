@extends('template.dashboard')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">Edit Outbound</h4>

        <x-alert key="success" action="success" />
        <x-alert key="errors" action="errors" />

        @php
            $lockAll = in_array($outbound->status, ['need_approved', 'approved', 'expired']);
            $lockTotal = in_array($outbound->status, ['approved', 'expired']);
            $userLevel = auth()->user()->level;
        @endphp

        <form action="{{ route('outbounds.update', $outbound->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- HEADER --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">No Transaksi</label>
                    <input type="text" class="form-control" value="{{ $outbound->transaction_number }}" readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Transaksi</label>
                    <input type="date" name="transaction_date" class="form-control"
                        value="{{ old('transaction_date', $outbound->transaction_date) }}" {{ $lockAll ? 'readonly' : '' }}>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Expired Date</label>
                    <input type="date" name="expired_date" class="form-control"
                        value="{{ old('expired_date', $outbound->expired_date) }}" {{ $lockAll ? 'readonly' : '' }}>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" {{ $lockTotal ? 'disabled' : '' }}>
                        @if ($userLevel === 'warehouse')
                            <option value="draft" {{ $outbound->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="need_approved" {{ $outbound->status === 'need_approved' ? 'selected' : '' }}>Need
                                Approved</option>
                        @elseif ($userLevel === 'manajement_warehouse')
                            <option value="approved" {{ $outbound->status === 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="expired" {{ $outbound->status === 'expired' ? 'selected' : '' }}>Expired</option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- WAREHOUSE --}}
            <div class="mb-4">
                <label class="form-label">Warehouse</label>
                <input type="text" class="form-control"
                    value="{{ $outbound->warehouseRef->name }} ({{ $outbound->warehouseRef->descrption }})" readonly>
                <input type="hidden" name="warehouse" value="{{ $outbound->warehouse }}">
            </div>

            {{-- DETAIL --}}
            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th width="120">Stock Available</th>
                            <th width="120">Stock On Hand</th>
                            <th width="120">Qty</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody">
                        @foreach ($outbound->details as $index => $detail)
                            @php $item = $items->firstWhere('id', $detail->item_id); @endphp
                            <tr>
                                <td>
                                    {{ $detail->item_name }}
                                    <input type="hidden" name="details[{{ $index }}][item_id]"
                                        value="{{ $detail->item_id }}">
                                </td>

                                <td class="text-center">{{ $item?->stock_avalaible ?? 0 }}</td>
                                <td class="text-center">{{ $item?->stock_on_hand ?? 0 }}</td>

                                <td>
                                    <input type="number" name="details[{{ $index }}][quantity]"
                                        class="form-control form-control-sm" value="{{ $detail->quantity }}" min="1"
                                        {{ $lockAll ? 'readonly' : '' }}>
                                </td>

                                <td class="text-center">
                                    @if (!$lockAll)
                                        <button type="button" class="btn btn-sm btn-danger btn-remove">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ADD ITEM (DRAFT ONLY) --}}
            @if ($outbound->status === 'draft')
                <div class="row g-2 mb-4">
                    <div class="col-md-5">
                        <select id="itemSelect" class="form-control">
                            <option value="">-- Pilih Item --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" data-name="{{ $item->name }}"
                                    data-avl="{{ $item->stock_avalaible }}" data-oh="{{ $item->stock_on_hand }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="number" id="qtyInput" class="form-control" min="1" placeholder="Qty">
                    </div>

                    <div class="col-md-4">
                        <button type="button" id="addItem" class="btn btn-success w-100">
                            Tambah Item
                        </button>
                    </div>
                </div>
            @endif

            {{-- ACTION --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('outbounds.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

                @if (!$lockTotal)
                    <button type="submit" class="btn btn-success">
                        Simpan Perubahan
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- SCRIPT (DRAFT ONLY) --}}
    @if ($outbound->status === 'draft')
        <script>
            let index = {{ $outbound->details->count() }};

            document.getElementById('addItem').addEventListener('click', function() {
                const select = document.getElementById('itemSelect');
                const qty = document.getElementById('qtyInput').value;

                if (!select.value || !qty) return;

                if (document.querySelector(`input[value="${select.value}"]`)) {
                    alert('Item sudah ada');
                    return;
                }

                const opt = select.options[select.selectedIndex];

                const row = `
                    <tr>
                        <td>
                            ${opt.dataset.name}
                            <input type="hidden"
                                name="details[${index}][item_id]"
                                value="${select.value}">
                        </td>
                        <td class="text-center">${opt.dataset.avl}</td>
                        <td class="text-center">${opt.dataset.oh}</td>
                        <td>
                            <input type="number"
                                name="details[${index}][quantity]"
                                class="form-control form-control-sm"
                                value="${qty}" min="1">
                        </td>
                        <td class="text-center">
                            <button type="button"
                                class="btn btn-sm btn-danger btn-remove">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                document.getElementById('detailBody')
                    .insertAdjacentHTML('beforeend', row);

                index++;
                document.getElementById('qtyInput').value = '';
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove')) {
                    e.target.closest('tr').remove();
                }
            });
        </script>
    @endif
@endsection
