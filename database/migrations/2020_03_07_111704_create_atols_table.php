<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_atols', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('operator_type_checker')->unsigned();
            $table->string('operator_type');
            $table->string('licence_no');
            $table->string('atol_certificate')->nullable();
            $table->date('date_of_first_issue');
            $table->date('renewal')->nullable();
            $table->date('date_of_expiry')->nullable();
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
        Schema::dropIfExists('tbl_ncaa_atols');
    }
}


