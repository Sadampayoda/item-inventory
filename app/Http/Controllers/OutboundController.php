<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOutboundRequest;
use App\Models\Item;
use App\Models\Outbound;
use App\Models\OutboundDetail;
use App\Models\Warehouse;
use App\Traits\GenerateTransactionNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutboundController extends Controller
{
    use GenerateTransactionNumber;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isWarehouse = auth()->check() && auth()->user()->level == 'warehouse';
        $outbounds = Outbound::with('warehouseRef');

        if(!$isWarehouse) {
            $outbounds->whereIn('status',['need_approved','approved','expired']);

        }

        return view('outbounds.index', [
            'outbounds' => $outbounds->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->check() && auth()->user()->level !== 'warehouse') {
            return redirect()->route('dashboard');
        }

        $warehouses = Warehouse::orderBy('name')->get();

        return view('outbounds.create', [
            'warehouses' => $warehouses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOutboundRequest $request)
    {
        $outbound = Outbound::create([
            'transaction_number' => $this->generateTransactionNumber(
                'OUT',
                'outbounds'
            ),
            'transaction_date' => $request->transaction_date,
            'warehouse_id'     => $request->warehouse_id,
            'expired_date'     => $request->expired_date,
            'status'           => 'draft',
        ]);

        return redirect()
            ->route('outbounds.edit', $outbound->id)
            ->with('success', 'Inbound berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outbound $outbound)
    {
        $isWarehouse = auth()->check() && auth()->user()->level == 'warehouse';
        if(!$isWarehouse && in_array($outbound->status, ['draft'])) {
            return redirect()->route('dashboard');
        }

        $outbound->load('details', 'warehouseRef');

        $warehouses = Warehouse::orderBy('name')->get();

        $codeWarehouse = $warehouses->find($outbound->warehouse_id);

        $items = Item::where('warehouse', $codeWarehouse->code)
            ->where('is_active', true)
            ->get();

        return view('outbounds.edit', compact(
            'outbound',
            'warehouses',
            'items'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outbound $outbound)
    {
        $isWarehouse = auth()->check() && auth()->user()->level == 'warehouse';
        if(!$isWarehouse && in_array($outbound->status, ['draft'])) {
            return redirect()->route('dashboard');
        }

        $outbound->load('details', 'warehouseRef');

        $warehouses = Warehouse::orderBy('name')->get();

        $codeWarehouse = $warehouses->find($outbound->warehouse_id);

        $items = Item::where('warehouse', $codeWarehouse->code)
            ->where('is_active', true)
            ->get();

        return view('outbounds.edit', compact(
            'outbound',
            'warehouses',
            'items'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outbound $outbound)
    {
        if (in_array($outbound->status, ['approved', 'expired'])) {
            return redirect()
                ->route('outbounds.index')
                ->with('errors', 'Outbound yang sudah approved / expired tidak dapat diubah');
        }

        $request->validate([
            'status' => 'required|in:draft,need_approved,approved,expired',
            'transaction_date' => 'required|date',
            'expired_date' => 'nullable|date',
            'details' => 'required|array|min:1',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $outbound) {

                foreach ($outbound->details as $detail) {
                    $item = Item::where('id', $detail->item_id)
                        ->where('warehouse', $outbound->warehouseRef->code)
                        ->lockForUpdate()
                        ->first();

                    if (!$item) continue;

                    if (in_array($outbound->status, ['draft', 'need_approved'])) {
                        $item->increment('stock_avalaible', $detail->quantity);
                    }

                    if ($outbound->status === 'approved') {
                        $item->increment('stock_on_hand', $detail->quantity);
                    }

                    $detail->delete();
                }

                $outbound->update([
                    'transaction_date' => $request->transaction_date,
                    'expired_date' => $request->expired_date,
                    'status' => $request->status,
                ]);

                foreach ($request->details as $detail) {
                    $item = Item::where('id', $detail['item_id'])
                        ->where('warehouse', $outbound->warehouseRef->code)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (in_array($request->status, ['draft', 'need_approved'])) {
                        if ($item->stock_avalaible < $detail['quantity']) {
                            throw new \Exception("Stock available {$item->name} tidak mencukupi");
                        }
                    }

                    if ($request->status === 'approved') {
                        if ($item->stock_on_hand < $detail['quantity']) {
                            throw new \Exception("Stock on hand {$item->name} tidak mencukupi");
                        }
                    }

                    OutboundDetail::create([
                        'outbound_id' => $outbound->id,
                        'item_id' => $item->id,
                        'item_name' => $item->name,
                        'quantity' => $detail['quantity'],
                    ]);

                    if (in_array($request->status, ['draft', 'need_approved'])) {
                        $item->decrement('stock_avalaible', $detail['quantity']);
                    }

                    if ($request->status === 'approved') {
                        $item->decrement('stock_avalaible', $detail['quantity']);
                        $item->decrement('stock_on_hand', $detail['quantity']);
                    }
                }
            });

            return redirect()
                ->route('outbounds.index')
                ->with('success', 'Outbound berhasil diperbarui');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outbound $outbound)
    {
        $isWarehouse = auth()->check() && auth()->user()->level == 'warehouse';
        if(!$isWarehouse) {
            return redirect()->route('dashboard');
        }

        if (in_array($outbound->status, ['approved', 'expired'])) {
            return redirect()
                ->route('outbounds.index')
                ->with('errors', 'Outbound yang sudah approved / expired tidak dapat dihapus');
        }

        try {
            DB::transaction(function () use ($outbound) {

                foreach ($outbound->details as $detail) {
                    $item = Item::where('id', $detail->item_id)
                        ->where('warehouse', $outbound->warehouseRef->code)
                        ->lockForUpdate()
                        ->first();

                    if (!$item) continue;

                    $item->increment('stock_avalaible', $detail->quantity);
                }

                $outbound->details()->delete();
                $outbound->delete();
            });

            return redirect()
                ->route('outbounds.index')
                ->with('success', 'Outbound berhasil dihapus');
        } catch (\Throwable $e) {
            return redirect()
                ->route('outbounds.index')
                ->with('errors', $e->getMessage());
        }
    }
}
