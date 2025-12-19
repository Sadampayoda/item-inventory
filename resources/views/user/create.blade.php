@extends('template.dashboard')

@section('content')
    @php
        $isEdit = isset($user);
    @endphp
    <x-alert key="success" action="success" />

    <form action="{{ $isEdit ? route('users.update', $user->id) : route('users.store') }}" method="POST">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        {{-- Nama --}}
        <div class="mb-3">
            <x-input-text name="name" label="Nama" :value="$isEdit ? $user->name : null" placeholder="Masukkan nama" />
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <x-input-text name="email" type="email" label="Email" :value="$isEdit ? $user->email : null" placeholder="Masukkan email" />
        </div>

        {{-- Level --}}
        <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-control border-2 rounded-0 @error('level') is-invalid @enderror">
                <option value="">-- Pilih Level --</option>
                @foreach ($levels as $level)
                    <option value="{{ $level }}"
                        {{ old('level', $isEdit ? $user->level : '') == $level ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $level)) }}
                    </option>
                @endforeach
            </select>

            @error('level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <x-input-text name="password" type="password"
                label="Password {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}" placeholder="Masukkan password" />
        </div>

        <div class="d-flex justify-content-end gap-4">
            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">
                Batal
            </button>
            <button type="submit" class="btn btn-success">
                {{ $isEdit ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
@endsection
