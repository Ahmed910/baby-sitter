<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerHourAndPricePerHourForMonthToMainOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_orders', function (Blueprint $table) {
            $table->double('price_per_hour')->nullable();
            $table->double('price_per_hour_for_month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_orders', function (Blueprint $table) {
            $table->dropColumn(['price_per_hour','price_per_hour_for_month']);
        });
    }
}
