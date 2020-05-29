<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_atos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('training_organization_id')->unsigned();
            $table->string('approval_no');
            $table->string('ato_certificate');
            $table->string('date_of_first_issue');
            $table->string('date_of_renewal');
            $table->string('date_of_expiry');
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
        Schema::dropIfExists('tbl_ncaa_atos');
    }
}
