@extends('template.dashboard')

@section('content')
    @php
        $isEdit = isset($item);
    @endphp

    <x-alert key="success" action="success" />
    <x-alert key="errors" action="errors" />

    <form action="{{ $isEdit ? route('items.update', $item->id) : route('items.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <x-input-text name="code" label="Kode Item" :value="$isEdit ? $item->code : null" />

        <x-input-text name="name" label="Nama Item" :value="$isEdit ? $item->name : null" />

        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" name="image" class="form-control">
            @if ($isEdit && $item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="mt-2" width="120">
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Warehouse</label>

            <select name="warehouse" class="form-control border-2 rounded-0 @error('warehouse') is-invalid @enderror">
                <option value="">-- Pilih Warehouse --</option>

                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->code }}"
                        {{ old('warehouse', $isEdit ? $item->warehouse : '') == $warehouse->code ? 'selected' : '' }}>
                        {{ $warehouse->code }} - {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>

            @error('warehouse')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <x-input-text
            name="stock_on_hand"
            type="number"
            label="Stock On Hand"
            :value="$isEdit ? $item->stock_on_hand : 0"
            {{ $isEdit ? 'readonly' : '' }}
        />

        <x-input-text
            name="stock_avalaible"
            type="number"
            label="Stock Available"
            :value="$isEdit ? $item->stock_avalaible : 0"
            readonly
        />

        <x-input-text name="min_stock" type="number" label="Minimum Stock" :value="$isEdit ? $item->min_stock : 0" />

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ old('is_active', $isEdit ? $item->is_active : 1) == 1 ? 'selected' : '' }}>Aktif
                </option>
                <option value="0" {{ old('is_active', $isEdit ? $item->is_active : 1) == 0 ? 'selected' : '' }}>Non
                    Aktif</option>
            </select>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
            <button class="btn btn-success">
                {{ $isEdit ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
@endsection


