<?php

// database/migrations/xxxx_xx_xx_add_price_to_purchase_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToPurchaseHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('purchase_histories', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}
