<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelPercentageColumnToBabySittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baby_sitters', function (Blueprint $table) {
            $table->smallInteger('level_percentage')->nullable()->after('level_experience');
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
            $table->dropColumn('level_percentage');
        });
    }
}
