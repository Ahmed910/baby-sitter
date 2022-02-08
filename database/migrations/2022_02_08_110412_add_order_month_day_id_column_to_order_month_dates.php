<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderMonthDayIdColumnToOrderMonthDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_month_dates', function (Blueprint $table) {
            $table->unsignedBigInteger('order_month_day_id')->nullable()->after('status');
            $table->foreign('order_month_day_id')->references('id')->on('order_month_days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_month_dates', function (Blueprint $table) {
            $table->dropColumn('order_month_day_id');
        });
    }
}
