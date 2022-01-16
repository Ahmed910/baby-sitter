<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinancialsToSitterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sitter_orders', function (Blueprint $table) {
            $table->double('app_profit')->default(0);
            $table->double('price_for_provider')->default(0);
            $table->double('app_profit_percentage')->default(0);
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sitter_orders', function (Blueprint $table) {
            $table->dropColumn(['app_profit','price_for_provider','app_profit_percentage','offer_id']);
        });
    }
}
