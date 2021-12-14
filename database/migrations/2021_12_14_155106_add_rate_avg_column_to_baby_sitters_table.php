<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateAvgColumnToBabySittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baby_sitters', function (Blueprint $table) {
            $table->float('rate_avg',5,2)->default(0)->after('total_num_of_student');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baby_sitters', function (Blueprint $table) {
            $table->dropColumn('rate_avg');
        });
    }
}
