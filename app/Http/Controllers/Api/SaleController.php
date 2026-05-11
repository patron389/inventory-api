<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Resources\SaleResource;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Store POS sale
     */
    public function store(StoreSaleRequest $request): SaleResource
    {
        $sale = $this->saleService->store(
            $request->validated(),
            auth()->user()
        );

        return new SaleResource($sale);
    }

    /**
     * Sales history
     */
    public function index(Request $request)
    {
        $sales = $this->saleService->getSales([
            'search' => $request->search,
        ]);

        return SaleResource::collection($sales);
    }

    // Show one transaction details 
    public function show(Sale $sale)
    {
        $sale = $this->saleService->getSaleById($sale);

        return new SaleResource($sale);
    }
}
