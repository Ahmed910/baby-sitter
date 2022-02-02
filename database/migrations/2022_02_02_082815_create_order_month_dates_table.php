<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderMonthDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_month_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('order_month_id')->nullable();
            $table->foreign('order_month_id')->references('id')->on('order_months')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_month_dates');
    }
}
