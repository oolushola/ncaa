<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFcopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_fcops', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('foreign_airline');
            $table->string('licence_no');
            $table->string('fcop_certificate');
            $table->boolean('part_18');
            $table->boolean('part_10');
            $table->boolean('part_17');
            $table->boolean('fcop_status');
            $table->string('date_fcop_issued');
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('tbl_ncaa_fcops');
    }
}
