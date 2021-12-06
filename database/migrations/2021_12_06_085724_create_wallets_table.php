<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount')->nullable();
            $table->decimal('wallet_before')->nullable();
            $table->decimal('wallet_after')->nullable();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->uuid('transferd_by')->nullable();
            $table->foreign('transferd_by')->references('id')->on('users')->onDelete('set null');
            $table->string('status')->default('pending');
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban_number')->nullable();
            $table->text('message_refused')->nullable();
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
        Schema::dropIfExists('wallets');
    }
}
