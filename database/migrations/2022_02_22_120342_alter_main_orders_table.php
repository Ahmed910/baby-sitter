<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMainOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_orders', function (Blueprint $table) {
            $table->string('agora_channel_name')->nullable()->after('to');
            $table->string('agora_expire_time_in_seconds')->nullable()->after('agora_channel_name');
            $table->string('agora_token')->nullable()->after('agora_expire_time_in_seconds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_orders', function (Blueprint $table) {
            $table->dropColumn(['agora_channel_name','agora_expire_time_in_seconds','agora_token']);
        });
    }
}
