<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sitter_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');

            $table->uuid('sitter_id')->nullable();
            $table->foreign('sitter_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');

            $table->longText('comment')->nullable();
            $table->decimal('price')->nullable();
            $table->string('qr_code')->nullable();

            $table->float('lat',8,4)->nullable();
            $table->float('lng',8,4)->nullable();
            $table->string('location')->nullable();
            $table->string('otp_code')->nullable();
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
        Schema::dropIfExists('sitter_orders');
    }
}
