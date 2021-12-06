<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainOrderIdToSitterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sitter_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('main_order_id')->nullable()->after('sitter_id');
            $table->foreign('main_order_id')->references('id')->on('main_orders')->onDelete('cascade');
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
           $table->dropColumn('main_order_id');
        });
    }
}
