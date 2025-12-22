@extends('template.dashboard')

@section('content')

<x-alert key="success" action="success" />
<x-alert key="errors" action="errors" />

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Outbound</h4>

        <a href="{{ route('outbounds.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Buat Outbound
        </a>
    </div>

    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light sticky-top">
                <tr>
                    <th width="40">No</th>
                    <th>No Transaksi</th>
                    <th>Tanggal</th>
                    <th>Warehouse</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($outbounds as $outbound)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $outbound->transaction_number }}</td>
                        <td>{{ $outbound->transaction_date }}</td>
                        <td>{{ $outbound->warehouseRef->name ?? '-' }}</td>
                        <td>
                            @if ($outbound->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif ($outbound->status === 'need_approved')
                                <span class="badge bg-warning text-dark">Need Approved</span>
                            @else
                                <span class="badge bg-success">Approved</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('outbounds.show', $outbound->id) }}"
                                class="btn btn-sm btn-info">
                                Detail
                            </a>

                            @if ($outbound->status !== 'approved')
                                <a href="{{ route('outbounds.edit', $outbound->id) }}"
                                    class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('outbounds.destroy', $outbound->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Outbound ini akan dibatalkan. Lanjutkan?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Data outbound belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
