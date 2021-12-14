<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdColumnToWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->bigInteger('order_id')->unsigned()->nullable()->after('user_id');
            $table->foreign('order_id')->references('id')->on('main_orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}
