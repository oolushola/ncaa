<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFoccAndMccTableWithAmoholderStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ncaa_focc_and_mccs', function (Blueprint $table) {
            $table->enum('amo_holder_status', ['0', '1'])->default(1);
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ncaa_focc_and_mccs', function (Blueprint $table) {
            $table->dropIfExists('amo_holder_status');
        });
    }
}
