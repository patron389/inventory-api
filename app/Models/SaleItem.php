<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Sale item belongs to sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Sale item belongs to product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
