<?php

// app/Models/Pharmacy.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'address', 'contact_number', 'is_approved', 'latitude', 'longitude', 'document1_path', 'document2_path', 'document3_path', 'sub_role', 
    ];

    protected $casts = [
        'sub_role' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function inventory()
    { 
        return $this->hasMany(Inventory::class);
    }
    
}
