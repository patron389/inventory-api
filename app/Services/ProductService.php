<?php

namespace App\Services;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class ProductService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Product::with([
            'category',
            'subcategory',
            'brand'
        ])->latest();

        // 🔎 Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhereHas('category', function ($categoryQuery) use ($search) {
                    $categoryQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('brand', function ($brandQuery) use ($search) {
                    $brandQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        // 📌 Status filter
        if (!empty($filters['status'])) {

            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            }

            if ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query->paginate(10);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        $product->delete();
    }
}
