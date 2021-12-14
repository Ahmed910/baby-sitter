<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWalletBeforeAndWalletAfterColumnsInWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('wallet_before')->default(0)->change();
            $table->decimal('wallet_after')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            //
        });
    }
}
