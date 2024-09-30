<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('main_products', function (Blueprint $table) {
            $table->string('brand_name')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('form')->nullable();
            $table->string('age_group')->nullable();
            // Add other fields as needed
        });
    }

    public function down()
    {
        Schema::table('main_products', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'generic_name', 'dosage', 'form', 'age_group']);
        });
    }
};
