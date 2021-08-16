<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificationTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ncaa_certification_trackers', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('certification_no');
            $table->string('date_assigned');
            $table->string('applicant_name');
            $table->string('certification_type');
            $table->string('cpm');
            $table->string('team_member');
            $table->string('start_date');
            $table->string('completion_date');
            $table->string('status');
            $table->string('aircraft_type');
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
        Schema::dropIfExists('tbl_ncaa_certification_trackers');
    }
}
