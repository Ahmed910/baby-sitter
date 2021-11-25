<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitters', function (Blueprint $table) {
            $table->id();
            $table->uuid('center_id')->nullable();
            $table->foreign('center_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->text('bio')->nullable();
            $table->integer('max_num_of_child_care')->nullable();
            $table->string('level_experience')->nullable();
            $table->integer('total_num_of_student')->nullable();
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
        Schema::dropIfExists('baby_sitters');
    }
}
