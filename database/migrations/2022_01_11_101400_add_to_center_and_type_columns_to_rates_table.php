<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToCenterAndTypeColumnsToRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->uuid('to_center')->nullable();
            $table->foreign('to_center')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('to_client')->nullable();
            $table->foreign('to_client')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropColumn(['to_client','to_center']);
        });
    }
}
