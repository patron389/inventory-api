<?php

namespace App\Services;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function summary()
    {
        return [
            'total_products' => Product::count(),
            'total_warehouses' => Warehouse::count(),
            'total_stock_quantity' => Stock::sum('quantity'),

            // Example: low stock threshold = below 5
            'low_stock_items' => Stock::where('quantity', '<', 5)->count(),
            'stock_per_warehouse' => $this->stockPerWarehouse(),
            
        ];
    }

    public function stockPerWarehouse()
    {
        return DB::table('stocks')
            ->join('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
            ->select(
                'warehouses.name as warehouse',
                DB::raw('SUM(stocks.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT stocks.product_id) as total_products')
            )
            ->groupBy('warehouses.id', 'warehouses.name')
            ->get();
    }

    public function lowStockReport($threshold = 10)
    {
        return \DB::table('stocks')
            ->join('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->select(
                'warehouses.name as warehouse',
                'products.name as product',
                'products.sku',
                'stocks.quantity'
            )
            ->where('stocks.quantity', '<', $threshold)
            ->orderBy('stocks.quantity', 'asc')
            ->get();
    }
}
