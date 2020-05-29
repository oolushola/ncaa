<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_aops', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('operator');
            $table->string('licence_no');
            $table->string('aop_certificate');
            $table->string('date_of_first_issue');
            $table->string('date_of_renewal');
            $table->string('date_of_expiry');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('tbl_ncaa_aops');
    }
}
