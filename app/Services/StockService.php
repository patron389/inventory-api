<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;

class StockService
{


    public function addStock(array $data)
    {
        return DB::transaction(function () use ($data) {

            $stock = Stock::where('warehouse_id', $data['warehouse_id'])
                ->where('product_id', $data['product_id'])
                ->first();

            if ($stock) {
                $stock->increment('quantity', $data['quantity']);
            } else {
                $stock = Stock::create([
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id'   => $data['product_id'],
                    'quantity'     => $data['quantity'],
                ]);
            }

            // Insert movement record
            StockMovement::create([
                'warehouse_id' => $data['warehouse_id'],
                'product_id'   => $data['product_id'],
                'user_id'      => Auth::id(),
                'type'         => 'add',
                'quantity'     => $data['quantity'],
                'remarks'      => 'Stock added',
            ]);

            return $stock->fresh();
        });
    }

    public function deductStock(array $data)
    {
        return DB::transaction(function () use ($data) {

            $stock = Stock::where('warehouse_id', $data['warehouse_id'])
                ->where('product_id', $data['product_id'])
                ->first();

            if (!$stock) {
                throw new \Exception('Stock not found for this warehouse and product.');
            }

            if ($stock->quantity < $data['quantity']) {
                throw new \Exception('Insufficient stock.');
            }

        $stock->decrement('quantity', $data['quantity']);

        // Insert movement record
        StockMovement::create([
            'warehouse_id' => $data['warehouse_id'],
            'product_id'   => $data['product_id'],
            'user_id'      => Auth::id(),
            'type'         => 'deduct',
            'quantity'     => $data['quantity'],
            'remarks'      => 'Stock deducted',
        ]);

            return $stock->fresh();
        });
    }
}