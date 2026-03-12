<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_warehouse_id',
        'to_warehouse_id',
        'user_id',
        'status',
    ];

    // Source warehouse
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    // Destination warehouse
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    // Transfer products
    public function items()
    {
        return $this->hasMany(TransferItem::class);
    }

    // User who performed the transfer
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}