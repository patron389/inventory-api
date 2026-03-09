<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StockService;
use App\Http\Resources\StockResource;
use Illuminate\Http\Request;
use App\Http\Requests\Stock\AddStockRequest;
use App\Http\Requests\Stock\DeductStockRequest;

class StockController extends Controller
{
    protected $service;

    public function __construct(StockService $service)
    {
        $this->service = $service;
    }

    public function add(AddStockRequest $request)
    {
        $stock = $this->service->addStock($request->validated());

        return new StockResource($stock);
    }

    public function deduct(DeductStockRequest $request)
    {
        try {
            $stock = $this->service->deductStock($request->validated());

            return new StockResource($stock);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getStocks(Request $request)
    {
        $stocks = $this->service->getStocks([
            'warehouse_id' => $request->warehouse_id,
            'search' => $request->search
        ]);

        return StockResource::collection($stocks);
    }
}
