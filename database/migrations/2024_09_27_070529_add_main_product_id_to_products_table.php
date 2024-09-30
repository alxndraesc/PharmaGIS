<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->unsignedBigInteger('general_id')->nullable()->after('id'); // Add the column after the 'id' field
        $table->foreign('general_id')->references('id')->on('main_products')->onDelete('cascade'); // Set up the foreign key
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['general_id']);
        $table->dropColumn('general_id');
    });
}

};
