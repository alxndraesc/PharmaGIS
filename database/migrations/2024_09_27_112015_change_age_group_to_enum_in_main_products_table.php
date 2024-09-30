<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('main_products', function (Blueprint $table) {
            $table->dropColumn('age_group'); 
        });
        Schema::table('main_products', function (Blueprint $table) {
            $table->enum('age_group', ['Children', 'Teen', 'Adult', 'Senior', 'General']); 
        });
    }

    public function down()
    {
        Schema::table('main_products', function (Blueprint $table) {
            $table->dropColumn('age_group');
        });

        Schema::table('main_products', function (Blueprint $table) {
            $table->string('age_group')->after('form');
        });
    }
};
