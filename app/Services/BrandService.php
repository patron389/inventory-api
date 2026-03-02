<?php

namespace App\Services;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
class BrandService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get paginated brand list.
     */
    public function getAll()
    {
        return Brand::latest()->paginate(10);
    }

    /**
     * Create new brand.
     */
    public function create(array $data): Brand
    {
        // Handle image upload if exists
        if (isset($data['image'])) {

            $data['image'] = $data['image']->store('brands', 'public');
        }

        return Brand::create($data);
    }

    /**
     * Update brand.
     */
    public function update(Brand $brand, array $data): Brand
    {
        if (isset($data['image'])) {

            // Delete old image if exists
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $data['image'] = $data['image']->store('brands', 'public');
        }

        $brand->update($data);

        return $brand;
    }

    /**
     * Delete brand.
     */
    public function delete(Brand $brand): void
    {
        $brand->delete();
    }
}
