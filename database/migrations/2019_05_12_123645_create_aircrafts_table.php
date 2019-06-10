<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAircraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_aircrafts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aoc_holder_id')->unsigned();
            $table->integer('aircraft_maker_id')->unsigned();
            $table->string('registration_marks');
            $table->string('aircraft_type');
            $table->string('aircraft_serial_number');
            $table->string('year_of_manufacture');
            $table->string('registration_date');
            $table->string('registered_owner');
            $table->string('c_of_a_status')->nullable();
            $table->string('c_of_a')->nullable();
            $table->string('weight');
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
        Schema::dropIfExists('tbl_ncaa_aircrafts');
    }
}
