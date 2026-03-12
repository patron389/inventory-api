<?php

namespace App\Services;
use App\Models\Stock;
use App\Models\Transfer;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransferService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // public function transfer(array $data)
    // {
    //     // Wrap entire transfer process in a database transaction
    //     // Ensures all operations succeed together or fail together
    //     return DB::transaction(function () use ($data) {

    //         // Prevent transferring within the same warehouse
    //         if ($data['from_warehouse_id'] == $data['to_warehouse_id']) {
    //             throw new \Exception('Cannot transfer to the same warehouse.');
    //         }

    //         // Get stock from source warehouse
    //         $fromStock = Stock::where('warehouse_id', $data['from_warehouse_id'])
    //             ->where('product_id', $data['product_id'])
    //             ->first();

    //         // If no stock exists in source warehouse, stop process
    //         if (!$fromStock) {
    //             throw new \Exception('Source warehouse has no stock.');
    //         }

    //         // Prevent negative stock (insufficient quantity)
    //         if ($fromStock->quantity < $data['quantity']) {
    //             throw new \Exception('Insufficient stock in source warehouse.');
    //         }

    //         // Deduct quantity from source warehouse (atomic DB operation)
    //         $fromStock->decrement('quantity', $data['quantity']);

    //         // Ensure destination warehouse has a stock row
    //         // If not, create it with initial quantity 0
    //         $toStock = Stock::firstOrCreate(
    //             [
    //                 'warehouse_id' => $data['to_warehouse_id'],
    //                 'product_id'   => $data['product_id'],
    //             ],
    //             [
    //                 'quantity' => 0
    //             ]
    //         );

    //         // Add quantity to destination warehouse
    //         $toStock->increment('quantity', $data['quantity']);

    //         // Record transfer transaction (main business record)
    //         $transfer = Transfer::create([
    //             'from_warehouse_id' => $data['from_warehouse_id'],
    //             'to_warehouse_id'   => $data['to_warehouse_id'],
    //             'product_id'        => $data['product_id'],
    //             'user_id'           => Auth::id(), // Track who performed transfer
    //             'quantity'          => $data['quantity'],
    //             'status'            => 'completed',
    //         ]);

    //         // Log movement: stock leaving source warehouse
    //         StockMovement::create([
    //             'warehouse_id' => $data['from_warehouse_id'],
    //             'product_id'   => $data['product_id'],
    //             'user_id'      => Auth::id(),
    //             'type'         => 'transfer_out',
    //             'quantity'     => $data['quantity'],
    //             'reference'    => $transfer->id, // Link movement to transfer
    //         ]);

    //         // Log movement: stock entering destination warehouse
    //         StockMovement::create([
    //             'warehouse_id' => $data['to_warehouse_id'],
    //             'product_id'   => $data['product_id'],
    //             'user_id'      => Auth::id(),
    //             'type'         => 'transfer_in',
    //             'quantity'     => $data['quantity'],
    //             'reference'    => $transfer->id,
    //         ]);

    //         // Return completed transfer record
    //         return $transfer;
    //     });
    // }
    public function transfer(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Prevent same warehouse transfer
            if ($data['from_warehouse_id'] == $data['to_warehouse_id']) {
                throw new \Exception('Cannot transfer to the same warehouse.');
            }

            // Create transfer record (header)
            $transfer = Transfer::create([
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id'   => $data['to_warehouse_id'],
                'user_id'           => Auth::id(),
                'status'            => 'completed',
            ]);

            foreach ($data['items'] as $item) {

                // Find stock in source warehouse
                $fromStock = Stock::where('warehouse_id', $data['from_warehouse_id'])
                    ->where('product_id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$fromStock) {
                    throw new \Exception('Source warehouse has no stock.');
                }

                if ($fromStock->quantity < $item['quantity']) {
                    throw new \Exception('Insufficient stock.');
                }

                // Deduct stock
                $fromStock->decrement('quantity', $item['quantity']);

                // Destination stock
                $toStock = Stock::firstOrCreate(
                    [
                        'warehouse_id' => $data['to_warehouse_id'],
                        'product_id'   => $item['product_id'],
                    ],
                    [
                        'quantity' => 0
                    ]
                );

                // Add stock
                $toStock->increment('quantity', $item['quantity']);

                // Save transfer item
                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                ]);

                // Log movement OUT
                StockMovement::create([
                    'warehouse_id' => $data['from_warehouse_id'],
                    'product_id'   => $item['product_id'],
                    'user_id'      => Auth::id(),
                    'type'         => 'transfer_out',
                    'quantity'     => $item['quantity'],
                    'reference'    => $transfer->id,
                ]);

                // Log movement IN
                StockMovement::create([
                    'warehouse_id' => $data['to_warehouse_id'],
                    'product_id'   => $item['product_id'],
                    'user_id'      => Auth::id(),
                    'type'         => 'transfer_in',
                    'quantity'     => $item['quantity'],
                    'reference'    => $transfer->id,
                ]);
            }

            return $transfer;
        });
    }
    public function getTransferMovement(array $filters = []) : LengthAwarePaginator
    {
            $query = Transfer::with([
                'items.product',
                'fromWarehouse',
                'toWarehouse',
                'user'
            ]);

            return $query->latest()->paginate(10);
    }
}
