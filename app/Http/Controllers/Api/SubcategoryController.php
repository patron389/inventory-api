<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SubcategoryService;
use App\Models\Subcategory;
use App\Http\Requests\Subcategory\StoreSubcategoryRequest;
use App\Http\Requests\Subcategory\UpdateSubcategoryRequest;
use App\Http\Resources\SubcategoryResource;

class SubcategoryController extends Controller
{
    protected $service;

    public function __construct(SubcategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $subcategories = $this->service->getAll();

        return SubcategoryResource::collection($subcategories);
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $subcategory = $this->service->create($request->validated());

        return new SubcategoryResource($subcategory);
    }

    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        $subcategory = $this->service->update($subcategory, $request->validated());

        return new SubcategoryResource($subcategory);
    }

    public function destroy(Subcategory $subcategory)
    {
        $this->service->delete($subcategory);

        return response()->json([
            'message' => 'Subcategory deleted successfully'
        ]);
    }
}
