<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalCostToPurchaseHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            $table->decimal('total_cost', 10, 2)->after('quantity')->nullable();
        });
    }

    public function down()
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            $table->dropColumn('total_cost');
        });
    }
}
