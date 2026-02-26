<?php

namespace App\Services;
use App\Models\StockMovement;

class StockMovementService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll(array $filters = [])
    {
        $query = StockMovement::with(['warehouse', 'product', 'user'])
            ->latest();

        // Optional filtering
        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->paginate(10);
    }
}
