<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalToPharmaciesTable extends Migration
{
    public function up()
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('contact_number');
            $table->string('document1_path')->nullable()->after('is_approved');
            $table->string('document2_path')->nullable()->after('document1_path');
            $table->string('document3_path')->nullable()->after('document2_path');
        });
    }

    public function down()
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn('is_approved');
            $table->dropColumn('document_path');
            $table->dropColumn(['document1_path', 'document2_path', 'document3_path']);
        });
    }
}