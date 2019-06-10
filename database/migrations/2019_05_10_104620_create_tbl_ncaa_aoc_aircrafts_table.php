<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblNcaaAocAircraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_aoc_aircrafts', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('aoc_holder_id')->unsigned();
            $table->integer('aircraft_maker_id')->unsigned();
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
        Schema::dropIfExists('tbl_ncaa_aoc_aircrafts');
    }
}
