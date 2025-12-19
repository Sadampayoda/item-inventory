@extends('template.dashboard')

@section('content')

<x-alert key="success" action="success" />
<x-alert key="errors" action="errors" />

<div class="container-fluid">
    <h4 class="mb-3">Buat Inbound</h4>

    {{-- FORM MASTER --}}
    <form action="{{ route('inbounds.store') }}" method="POST" class="border p-4 mb-4">
        @csrf

        <div class="row">
            <div class="col-md-4">
                <x-input-text
                    name="transaction_date"
                    type="date"
                    label="Tanggal Transaksi"
                    :value="now()->format('Y-m-d')"
                />
            </div>

            <div class="col-md-4">
                <label class="form-label">Warehouse</label>
                <select name="warehouse_id"
                    class="form-control border-2 rounded-0 @error('warehouse_id') is-invalid @enderror">
                    <option value="">-- Pilih Warehouse --</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">
                            {{ $warehouse->code }} - {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>

                @error('warehouse_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-3 text-end">
            <button class="btn btn-success">
                Simpan & Lanjut
            </button>
        </div>
    </form>

    <x-alert key="info" message="Silakan simpan inbound terlebih dahulu sebelum menambahkan item" justify_text="text-start" />
</div>
@endsection
