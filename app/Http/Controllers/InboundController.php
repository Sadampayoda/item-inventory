<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInboundRequest;
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Item;
use App\Models\Warehouse;
use App\Traits\GenerateTransactionNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InboundController extends Controller
{
    use GenerateTransactionNumber;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inbounds = Inbound::with('warehouseRef')->get();

        return view('inbounds.index', [
            'inbounds' => $inbounds,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->get();

        return view('inbounds.create', [
            'warehouses' => $warehouses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateInboundRequest $request)
    {
        $inbound = Inbound::create([
            'transaction_number' => $this->generateTransactionNumber(
                'IN',
                'inbounds'
            ),
            'transaction_date' => $request->transaction_date,
            'warehouse_id'     => $request->warehouse_id,
            'expired_date'     => $request->expired_date,
            'status'           => 'draft',
        ]);

        return redirect()
            ->route('inbounds.edit', $inbound->id)
            ->with('success', 'Inbound berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inbound $inbound)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inbound $inbound)
    {
        $inbound->load('details', 'warehouseRef');

        $warehouses = Warehouse::orderBy('name')->get();

        $codeWarehouse = $warehouses->find($inbound->warehouse_id);

        $items = Item::where('warehouse', $codeWarehouse->code)
            ->where('is_active', true)
            ->get();

        return view('inbounds.edit', compact(
            'inbound',
            'warehouses',
            'items'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inbound $inbound)
    {
        if (in_array($inbound->status, ['approved', 'expired'])) {
            return redirect()
                ->route('inbounds.index')
                ->with('errors', 'Inbound yang sudah approved / expired tidak dapat diubah');
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
            DB::transaction(function () use ($request, $inbound) {

                foreach ($inbound->details as $detail) {
                    $item = Item::where('id', $detail->item_id)
                        ->where('warehouse', $inbound->warehouseRef->code)
                        ->lockForUpdate()
                        ->first();

                    if (!$item) continue;

                    if ($inbound->status === 'approved') {
                        $item->decrement('stock_avalaible', $detail->quantity);
                        $item->decrement('stock_on_hand', $detail->quantity);
                    }

                    $detail->delete();
                }

                $inbound->update([
                    'transaction_date' => $request->transaction_date,
                    'expired_date' => $request->expired_date,
                    'status' => $request->status,
                ]);

                foreach ($request->details as $detail) {
                    $item = Item::where('id', $detail['item_id'])
                        ->where('warehouse', $inbound->warehouseRef->code)
                        ->lockForUpdate()
                        ->firstOrFail();

                    InboundDetail::create([
                        'inbound_id' => $inbound->id,
                        'item_id' => $item->id,
                        'item_name' => $item->name,
                        'quantity' => $detail['quantity'],
                    ]);


                    if ($request->status === 'approved') {
                        $item->increment('stock_avalaible', $detail['quantity']);
                        $item->increment('stock_on_hand', $detail['quantity']);
                    }
                }
            });

            return redirect()
                ->route('inbounds.index')
                ->with('success', 'Inbound berhasil diperbarui');
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
    public function destroy(Inbound $inbound)
    {
        //
    }
}
