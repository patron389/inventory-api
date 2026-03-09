<?php

namespace App\Services;
use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class WarehouseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getAll(array $filters = []) : LengthAwarePaginator
    {
        $query = Warehouse::query();
        // 🔎 Search filter (by name only)
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where('name', 'like', "%{$search}%");
        }
        
        if (!empty($filters['status'])) {

            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            }

            if ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }
        return $query->latest()->paginate(10);
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
