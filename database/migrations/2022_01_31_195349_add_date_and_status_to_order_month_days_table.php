<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateAndStatusToOrderMonthDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_month_days', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('end_time');
            $table->date('date')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_month_days', function (Blueprint $table) {
            $table->dropColumn(['status','date']);
        });
    }
}
