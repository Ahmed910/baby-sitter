<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->uuid('from')->nullable();
            $table->foreign('from')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('to')->nullable();
            $table->foreign('to')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('main_orders')->onDelete('cascade');

            $table->float('rate',4,2)->nullable();

            $table->text('review')->nullable();

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
        Schema::dropIfExists('rates');
    }
}
