<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAircraftMakeAircraftTypeToLocalAmo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ncaa_amo_locals', function (Blueprint $table) {
            $table->string('aircraft_maker_id');
            $table->string('aircraft_type_id')->nullable();
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
            $table->dropColumn('aircraft_maker_id');
            $table->dropColumn('aircraft_type_id');
        });
    }
}
