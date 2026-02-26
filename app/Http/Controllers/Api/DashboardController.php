<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function summary()
    {
        return response()->json(
            $this->service->summary()
        );
    }

    public function stockPerWarehouse()
    {
        return response()->json(
            $this->service->stockPerWarehouse()
        );
    }

    public function lowStock()
    {
        return response()->json(
            $this->service->lowStockReport()
        );
    }
}
