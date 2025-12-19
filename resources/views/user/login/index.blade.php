@extends('index')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="p-4" style="width: 600px;">
            <h4 class="text-center mb-0 text-success">Inventory Barang</h4>
            <p class="text-center mt-0 mb-3 small text-muted pb-2">
                Sistem stok barang yang akurat dan terjamin aman
            </p>
            @if ($errors->has('login'))
                <div class="alert alert-danger text-center">
                    Email / Username atau password salah
                </div>
            @endif
            <form method="POST" action="{{ route('auth.authentication') }}" class="border-top border-bottom p-5">
                @csrf

                <!-- Email / Username -->
                <div class="mb-3">
                    <x-input-text name="email" label="Email / Username" placeholder="Masukkan email atau username" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-text name="password" type="password" label="Password" placeholder="Masukkan password" />
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
