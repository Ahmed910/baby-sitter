<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');

            $table->uuid('sitter_id')->nullable();
            $table->foreign('sitter_id')->references('id')->on('users')->onDelete('set null');

            $table->uuid('center_id')->nullable();
            $table->foreign('center_id')->references('id')->on('users')->onDelete('set null');

            $table->string('to')->nullable();
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
        Schema::dropIfExists('main_orders');
    }
}
