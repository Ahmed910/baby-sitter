<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayTypeColumnToSitterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sitter_orders', function (Blueprint $table) {
            $table->string('pay_type')->nullable()->after('service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sitter_orders', function (Blueprint $table) {
            $table->dropColumn('pay_type');
        });
    }
}
