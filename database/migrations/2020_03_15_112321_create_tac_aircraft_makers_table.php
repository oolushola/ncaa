<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTacAircraftMakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_tac_aircraft_makers', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('tac_id')->unisgned();
            $table->integer('aircraft_maker_id')->unsigned();
            $table->integer('aircraft_type_id')->unisgned();
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
        Schema::dropIfExists('tbl_ncaa_tac_aircraft_makers');
    }
}
