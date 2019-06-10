<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblNcaaAssignOperationSpecAocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_assign_operation_spec_aocs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aoc_holder_id')->unsigned();
            $table->integer('operation_type_id')->unsigned();
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
        Schema::dropIfExists('tbl_ncaa_assign_operation_spec_aocs');
    }
}
