<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignAmoRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_foreign_amo_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('foreign_amo_id')->unsigned();
            $table->integer('aircraft_maker_id')->unsigned();
            $table->integer('aircraft_type_id')->nullable()->unsigned();
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
        Schema::dropIfExists('tbl_ncaa_foreign_amo_ratings');
    }
}
