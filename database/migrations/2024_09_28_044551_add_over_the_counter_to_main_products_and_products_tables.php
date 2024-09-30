<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('main_products', function (Blueprint $table) {
        $table->boolean('over_the_counter');
    });
}

public function down()
{
    Schema::table('main_products', function (Blueprint $table) {
        $table->dropColumn('over_the_counter');
    });
}

};
