<?php

namespace App\Services;

class SubcategoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get paginated subcategories with category relationship.
     */
    public function getAll()
    {
        return Subcategory::with('category')
            ->latest()
            ->paginate(10);
    }

    /**
     * Create new subcategory.
     */
    public function create(array $data): Subcategory
    {
        return Subcategory::create($data);
    }

    /**
     * Update subcategory.
     */
    public function update(Subcategory $subcategory, array $data): Subcategory
    {
        $subcategory->update($data);

        return $subcategory;
    }

    /**
     * Delete subcategory.
     */
    public function delete(Subcategory $subcategory): void
    {
        $subcategory->delete();
    }
}
