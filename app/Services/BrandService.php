<?php

namespace App\Services;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        // Start query builder
        $query = Brand::query();
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
        // Order latest and paginate
        return $query->latest()->paginate(10);
    }

    // Upload image 
    private function uploadImage(UploadedFile $image): string
    {
        $filename = uniqid() . '.jpg';

        $img = Image::read($image)
            ->resize(800, 800) // resize image
            ->toJpeg(80); // encode jpeg with 80% quality

        Storage::disk('public')->put("brands/{$filename}", $img);

        return "brands/{$filename}";
    }
    /**
     * Create new brand.
     */
    
    public function create(array $data): Brand
    {
        // Handle image upload if exists
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return Brand::create($data);
    }
    // to delete old image to be used in the update function
    private function deleteOldImage(Brand $brand): void
    {
        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }
    }
    /**
     * Update brand.
     */

    public function update(Brand $brand, array $data): Brand
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {

            // Delete old image
            $this->deleteOldImage($brand);

            // Upload resized image
            $data['image'] = $this->uploadImage($data['image']);
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
