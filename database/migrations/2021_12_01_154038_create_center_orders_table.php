<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCenterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('center_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('baby_sitter_id')->nullable();
            $table->foreign('baby_sitter_id')->references('id')->on('baby_sitters')->onDelete('set null');

            $table->uuid('center_id')->nullable();
            $table->foreign('center_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');

            $table->longText('comment')->nullable();
            $table->decimal('price')->nullable();

            $table->string('status')->default('pending');
            $table->string('transaction_id')->nullable();
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
        Schema::dropIfExists('center_orders');
    }
}
