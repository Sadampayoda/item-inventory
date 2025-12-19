@extends('template.dashboard')

@section('content')
    @php
        $isEdit = isset($warehouse);
    @endphp

    <x-alert key="success" action="success" />
    <x-alert key="error" action="errors" />


    <h5 class="mb-0">
        {{ $isEdit ? 'Edit Warehouse' : 'Tambah Warehouse' }}
    </h5>

    <form action="{{ $isEdit ? route('warehouses.update', $warehouse->id) : route('warehouses.store') }}" method="POST">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        {{-- Kode --}}
        <div class="mb-3">
            <x-input-text name="code" label="Kode Warehouse" :value="$isEdit ? $warehouse->code : null" placeholder="Contoh: WH-001" />
        </div>

        {{-- Nama --}}
        <div class="mb-3">
            <x-input-text name="name" label="Nama Warehouse" :value="$isEdit ? $warehouse->name : null" placeholder="Nama warehouse" />
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="descrption" rows="3"
                class="form-control border-2 rounded-0 @error('descrption') is-invalid @enderror">{{ old('descrption', $isEdit ? $warehouse->descrption : '') }}</textarea>

            @error('descrption')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                Kembali
            </a>
            <button class="btn btn-success">
                {{ $isEdit ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
@endsection

