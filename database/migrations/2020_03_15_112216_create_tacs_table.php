<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTacsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_tacs', function (Blueprint $table) {
            $table->increments('id')->unisgned();
            $table->integer('aircraft_maker_id')->unsigned();
            $table->string('tc_acceptance_approval')->nullable();
            $table->string('certificate_no')->nullable();
            $table->string('date_issued')->nullable();
            $table->integer('tc_holder')->unsigned()->nullable();
            $table->string('original_tc_issued_by')->nullable();
            $table->string('tc_no')->nullable();
            $table->text('tcds_latest_revision')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('tbl_ncaa_tacs');
    }
}
