@extends('template.dashboard')

@section('content')
    <x-alert key="success" action="success" />

    <div class="d-flex justify-content-between mb-3">
        <h5>Data Warehouse</h5>
        <a href="{{ route('warehouses.create') }}" class="btn btn-success">
            Tambah Warehouse
        </a>
    </div>

    <div class="table-responsive" style="max-height: 450px;">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light sticky-top">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warehouses as $warehouse)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $warehouse->code }}</td>
                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->descrption ?? '-' }}</td>
                        <td>
                            <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin hapus warehouse ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Data warehouse kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
