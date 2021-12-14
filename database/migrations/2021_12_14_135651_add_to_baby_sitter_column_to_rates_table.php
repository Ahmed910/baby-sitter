<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToBabySitterColumnToRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->unsignedBigInteger('to_baby_sitter')->nullable()->after('to');
            $table->foreign('to_baby_sitter')->references('id')->on('baby_sitters')->onDelete('cascade');
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
            $table->dropColumn('to_baby_sitter');
        });
    }
}
