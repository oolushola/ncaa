<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_foccs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aoc_holder_id')->unsigned();
            $table->string('focc_no');
            $table->integer('aircraft_type')->unsigned();
            $table->string('aircraft_reg_no');
            $table->string('type_of_operations');
            $table->string('date_of_first_issue');
            $table->string('renewal')->nullable();
            $table->string('valid_till')->nullable();
            $table->text('approval_import')->nullable();
            $table->string('inspector')->nullable();
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
        Schema::dropIfExists('tbl_ncaa_foccs');
    }
}
