<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Services\BrandService;

class BrandController extends Controller
{
    protected $service;

    public function __construct(BrandService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $brands = $this->service->getAll();

        return BrandResource::collection($brands);
    }

    public function store(StoreBrandRequest $request)
    {
        $brand = $this->service->create($request->validated());

        return new BrandResource($brand);
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand = $this->service->update($brand, $request->validated());

        return new BrandResource($brand);
    }

    public function destroy(Brand $brand)
    {
        $this->service->delete($brand);

        return response()->json([
            'message' => 'Brand deleted successfully'
        ]);
    }
}
