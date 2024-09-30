<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('main_products', function (Blueprint $table) {
        $table->id(); 
        $table->string('general_id')->unique(); 
        $table->string('product_name'); 
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('main_products');
}
};
