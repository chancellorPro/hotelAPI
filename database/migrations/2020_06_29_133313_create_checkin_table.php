<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkin', function (Blueprint $table) {
            $table->id();
            $table->string('client_name', 255);
            $table->string('phone', 32);
            $table->integer('voted_capacity')->default(1);
            $table->dateTime('checkin_start');
            $table->dateTime('checkin_end')->nullable();
            $table->unsignedInteger('room_id')->index('room_id_index');
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
        Schema::dropIfExists('checkin');
    }
}
