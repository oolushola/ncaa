<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoccAndMccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_focc_and_mccs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->enum('operator_type', ['1', '2'])->nullable();
            $table->string('operator');
            $table->string('focc_no');
            $table->string('mcc_no');
            $table->integer('state_of_registry_id')->unsigned();
            $table->string('registered_owner');
            $table->integer('aircraft_maker_id')->unsigned();
            $table->integer('aircraft_type_id')->unsigned();
            $table->integer('aircraft_reg_no_id')->unsigned();
            $table->string('date_of_first_issue');
            $table->string('renewal')->nullable();
            $table->string('valid_till')->nullable();
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
        Schema::dropIfExists('tbl_ncaa_focc_and_mccs');
    }
}
