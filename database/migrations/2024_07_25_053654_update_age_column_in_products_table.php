<?php

// database/migrations/xxxx_xx_xx_update_age_column_in_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAgeColumnInProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the existing column
            $table->dropColumn('age');
        });

        Schema::table('products', function (Blueprint $table) {
            // Add the enum column
            $table->enum('age_group', ['Children', 'Teen', 'Adult', 'Senior', 'General'])->after('price');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the enum column
            $table->dropColumn('age_group');
        });

        Schema::table('products', function (Blueprint $table) {
            // Re-add the original column
            $table->string('age')->after('price');
        });
    }
}
