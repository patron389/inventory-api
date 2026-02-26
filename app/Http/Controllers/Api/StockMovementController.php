<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StockMovementService;
use App\Http\Resources\StockMovementResource;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    protected $service;

    public function __construct(StockMovementService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $movements = $this->service->getAll($request->all());

        return StockMovementResource::collection($movements);
    }
}
