<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Stock;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleService
{
    public function store(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {

            /*
            |--------------------------------------------------------------------------
            | Variables
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            /*
            |--------------------------------------------------------------------------
            | Compute subtotal
            |--------------------------------------------------------------------------
            */

            foreach ($data['items'] as $item) {

                $product = Product::findOrFail($item['product_id']);

                $subtotal += $product->price * $item['quantity'];
            }

            /*
            |--------------------------------------------------------------------------
            | Totals
            |--------------------------------------------------------------------------
            */

            $discount = $data['discount'] ?? 0;
            $tax = $data['tax'] ?? 0;

            $totalAmount = ($subtotal - $discount) + $tax;

            /*
            |--------------------------------------------------------------------------
            | Validate payment
            |--------------------------------------------------------------------------
            */

            if ($data['payment_amount'] < $totalAmount) {
                abort(422, 'Insufficient payment amount.');
            }

            /*
            |--------------------------------------------------------------------------
            | Create Sale
            |--------------------------------------------------------------------------
            */

            $sale = Sale::create([
                'invoice_no' => 'INV-' . now()->format('YmdHis'),

                'warehouse_id' => $data['warehouse_id'],

                'user_id' => $user->id,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'tax' => $tax,

                'total_amount' => $totalAmount,

                'payment_amount' => $data['payment_amount'],

                'change_amount' => $data['payment_amount'] - $totalAmount,

                'remarks' => $data['remarks'] ?? null,

                'status' => 'completed',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Process Items
            |--------------------------------------------------------------------------
            */

            foreach ($data['items'] as $item) {

                $product = Product::findOrFail($item['product_id']);

                /*
                |--------------------------------------------------------------------------
                | Lock stock row
                |--------------------------------------------------------------------------
                */

                $stock = Stock::where('warehouse_id', $data['warehouse_id'])
                    ->where('product_id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$stock || $stock->quantity < $item['quantity']) {

                    abort(422, "{$product->name} has insufficient stock.");
                }

                /*
                |--------------------------------------------------------------------------
                | Deduct stock
                |--------------------------------------------------------------------------
                */

                $stock->decrement('quantity', $item['quantity']);

                /*
                |--------------------------------------------------------------------------
                | Create sale item
                |--------------------------------------------------------------------------
                */

                SaleItem::create([
                    'sale_id' => $sale->id,

                    'product_id' => $product->id,

                    'quantity' => $item['quantity'],

                    'price' => $product->price,

                    'subtotal' => $product->price * $item['quantity'],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Create stock movement
                |--------------------------------------------------------------------------
                */

                StockMovement::create([
                    'warehouse_id' => $data['warehouse_id'],

                    'product_id' => $product->id,

                    'user_id' => $user->id,

                    'type' => 'sale',

                    'quantity' => $item['quantity'],

                    'reference' => $sale->invoice_no,

                    'remarks' => 'POS Sale',
                ]);
            }

            return $sale->load([
                'items.product',
                'warehouse',
                'user',
            ]);
        });
    }

    public function getSales(array $filters)
    {
        return Sale::with([
                'items.product',
                'warehouse',
                'user',
            ])

            // Search by invoice number
            ->when($filters['search'] ?? null, function ($query, $search) {

                $query->where('invoice_no', 'like', "%{$search}%");
            })

            // Filter by warehouse
            ->when($filters['warehouse_id'] ?? null, function ($query, $warehouseId) {

                $query->where('warehouse_id', $warehouseId);
            })

            // Filter by status
            ->when($filters['status'] ?? null, function ($query, $status) {

                $query->where('status', $status);
            })

            ->latest()

            ->paginate(10);
    }

    public function getSaleById(Sale $sale)
    {
        return $sale->load([
            'items.product',
            'warehouse',
            'user',
        ]);
    }
}