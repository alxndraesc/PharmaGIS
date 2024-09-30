<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainProduct extends Model
{
    use HasFactory;

    protected $table = 'main_products';

    protected $fillable = ['brand_name', 'generic_name', 'dosage', 'form', 'age_group', 'over_the_counter'
    ];

    protected static function booted()
    {
        static::creating(function ($mainProduct) {
            // Find the max existing general_id, if none exists, start at 10000000
            $maxGeneralId = MainProduct::max('general_id') ?? 9999999;
            
            // Increment by 1 for the next general_id
            $mainProduct->general_id = $maxGeneralId + 1;
        });
    }
}


