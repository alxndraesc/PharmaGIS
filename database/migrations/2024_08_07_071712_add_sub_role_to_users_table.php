<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_add_sub_role_to_pharmacies_table.php
public function up()
{
    Schema::table('pharmacies', function (Blueprint $table) {
        $table->enum('sub_role', ['owner', 'employee'])->nullable();
    });
}

public function down()
{
    Schema::table('pharmacies', function (Blueprint $table) {
        $table->dropColumn('sub_role');
    });
}

};
