<?php

namespace App\Services;
use App\Models\Warehouse;

class WarehouseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getAll()
    {
        return Warehouse::latest()->paginate();
    }

    public function create(array $data)
    {
        return Warehouse::create($data);
    }

    public function update(Warehouse $warehouse, array $data)
    {
        $warehouse->update($data);
        return $warehouse;
    }

    public function delete(Warehouse $warehouse)
    {
        // Future safety: prevent delete if has stocks
        $warehouse->delete();
    }
}
