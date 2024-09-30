<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id', 'brand_name', 'generic_name', 'dosage', 'form', 'description', 'price', 'price_bought', 'age_group', 'over_the_counter', 'category_id'
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
