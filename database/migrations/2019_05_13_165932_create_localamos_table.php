<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_amo_locals', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('holder_criteria', ['1', '2']);
            $table->integer('aoc_holder_id')->unsigned()->nullable('0');
           $table->string('non_aoc_holder')->nullable();
            $table->string('amo_approval_number');
            $table->string('amo_approval_number_file');
            $table->text('ratings_capabilities');
            $table->string('maintenance_locations');
            $table->timestamp('expiry');
            $table->string('amo_pm_aprvl_pg_lep');
            $table->string('amo_pm_aprvl_pg_lep_file');
            $table->string('extention');
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
        Schema::dropIfExists('tbl_ncaa_amo_locals');
    }
}
