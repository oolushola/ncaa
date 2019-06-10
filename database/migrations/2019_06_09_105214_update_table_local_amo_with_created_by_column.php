<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableLocalAmoWithCreatedByColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ncaa_amo_locals', function (Blueprint $table) {
            $table->string('created_by');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ncaa_amo_locals', function (Blueprint $table) {
            $table->dropIfExists('created_by');
        });
    }
}
