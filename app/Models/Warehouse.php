<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'contact_person',
        'contact_phone',
        'contact_email',
        'description',
        'is_active',
    ];
    
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
