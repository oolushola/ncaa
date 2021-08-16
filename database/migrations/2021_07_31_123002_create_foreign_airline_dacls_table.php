<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignAirlineDaclsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_foreign_airline_dacls', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('airline_name');
            $table->string('dacl_no');
            $table->string('dacl_certificate');
            $table->string('dacl_issue_date');
            $table->string('aoc_opspec');
            $table->string('aoc_expiry_date');
            $table->string('country');
            $table->text('remarks');
            $table->string('created_by');
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
        Schema::dropIfExists('tbl_ncaa_foreign_airline_dacls');
    }
}
