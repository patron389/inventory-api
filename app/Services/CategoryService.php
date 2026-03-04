<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class CategoryService
{
    /**
     * Get paginated category list.
     * Separated into service for clean controller logic
     * and future extensibility (filters, caching, etc).
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Category::query();
        // 🔎 Search filter (by name only)
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where('name', 'like', "%{$search}%");
        }

        // Status filter
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

    /**
     * Create a new category.
     * Validation is handled in FormRequest,
     * so we trust $data here.
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update an existing category.
     * Business logic could be added here later
     * (e.g. prevent update if linked to products).
     */
    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category;
    }

    /**
     * Delete category.
     * ON DELETE CASCADE will handle subcategories/products.
     */
    public function delete(Category $category): void
    {
        $category->delete();
    }
}