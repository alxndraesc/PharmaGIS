<?php

// app/Models/Inventory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'pharmacy_id', 'product_id', 'quantity', 'status', 'expiration_date'
    ];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    protected $dates = ['expiration_date'];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function getStatusAttribute($value)
    {
        if ($this->quantity <= 0) {
            return 'Sold Out';
        }

        if ($value === 'discontinued') {
            return 'Discontinued';
        }

        if ($value === 'on_order') {
            return 'On Order';
        }

        return 'Available';
    }
}
