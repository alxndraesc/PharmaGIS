<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    use HasFactory;

    protected $fillable = ['pharmacy_id', 'product_id', 'quantity', 'price', 'total_cost', 'purchased_at'];

    protected $casts = [
        'purchased_at' => 'datetime',
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
