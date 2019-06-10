<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_foreign_amos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('amo_holder');
            $table->integer('regional_country_id')->unsigned();
            $table->string('moe_reference');
            $table->text('approvals');
            $table->text('ratings_capabilities');
            $table->string('amo_number');
            $table->string('amo');
            $table->timestamp('expiry');
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
        Schema::dropIfExists('tbl_ncaa_foreign_amos');
    }
}
