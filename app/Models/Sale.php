<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'warehouse_id',
        'user_id',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'payment_amount',
        'change_amount',
        'status',
        'remarks',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Sale belongs to warehouse
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Sale belongs to cashier/user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Sale has many sale items
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
    
}