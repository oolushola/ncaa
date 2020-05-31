<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAircraftTableWithMajorChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ncaa_aircrafts', function (Blueprint $table) {
            $table->text('major_checks')->nullable()->before('created_at');
            $table->text('aircraft_serviceability_status')->nullable()->after('major_checks');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ncaa_aircrafts', function (Blueprint $table) {
            $table->dropColumn('major_checks');
            $table->dropColumn('aircraft_serviceability_status');
        });
    }
}
