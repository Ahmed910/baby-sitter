<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerHourForMonthToOrderMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_months', function (Blueprint $table) {
            $table->double('price_per_hour_for_month')->nullable()->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_month', function (Blueprint $table) {
            $table->dropColumn('price_per_hour_for_month');
        });
    }
}
