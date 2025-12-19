@extends('template.dashboard')

@section('content')
    <x-alert key="success" action="success" />

    <a href="{{ route('items.create') }}" class="btn btn-success mb-3">
        <i class="fa fa-plus"></i> Tambah Item
    </a>

    <div class="table-responsive" style="max-height:450px;overflow-y:auto">
        <table class="table table-bordered">
            <thead class="table-light sticky-top">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Warehouse</th>
                    <th>Stock</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" width="50">
                            @endif
                        </td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->warehouse }}</td>
                        <td>{{ $item->stock_avalaible }}</td>
                        <td>
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
