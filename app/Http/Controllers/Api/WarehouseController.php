<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseResource;
use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;

class WarehouseController extends Controller
{
    protected $service;

    public function __construct(WarehouseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'status'
        ]);
        return WarehouseResource::collection(
            $this->service->getAll($filters)
        );
        
    }

    public function store(StoreWarehouseRequest $request)
    {
        $warehouse = $this->service->create($request->validated());
        return new WarehouseResource($warehouse);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse = $this->service->update($warehouse, $request->validated());
        return new WarehouseResource($warehouse);
    }

    public function destroy(Warehouse $warehouse)
    {
        $this->service->delete($warehouse);

        return response()->json([
            'message' => 'Warehouse deleted successfully'
        ]);
    }
}