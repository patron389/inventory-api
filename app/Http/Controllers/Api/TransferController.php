<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TransferService;
use App\Http\Requests\Transfer\StoreTransferRequest;
use App\Http\Resources\TransferResource;
class TransferController extends Controller
{
    protected $service;

    public function __construct(TransferService $service)
    {
        $this->service = $service;
    }

    public function store(StoreTransferRequest $request)
    {
        try {
            $transfer = $this->service->transfer($request->validated());

            return response()->json([
                'message' => 'Transfer completed successfully.',
                'data' => $transfer
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'status'
        ]);
        return TransferResource::collection(
            $this->service->getTransferMovement($filters)
        );
    }
}
