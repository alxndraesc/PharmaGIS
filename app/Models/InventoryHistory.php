<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'product_id',
        'quantity',
        'status',
        'updated_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'updated_at' => 'datetime',
    ];
    
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}