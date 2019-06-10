<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblNcaaAcosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_acos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('aoc_holder');
            $table->string('aoc_certificate');
            $table->string('issued_date');
            $table->string('validity');
            $table->string('ops_spec');
            $table->string('part_g');
            $table->enum('remarks', ['1', '2', '3', '4']);
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
        Schema::dropIfExists('tbl_ncaa_acos');
    }
}
