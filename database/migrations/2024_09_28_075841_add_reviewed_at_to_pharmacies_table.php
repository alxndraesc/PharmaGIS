<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('pharmacies', function (Blueprint $table) {
        $table->timestamp('reviewed_at')->nullable();
    });
}

public function down()
{
    Schema::table('pharmacies', function (Blueprint $table) {
        $table->dropColumn('reviewed_at');
    });
}

};
