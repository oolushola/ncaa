<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAocCertificateNumberColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ncaa_acos', function (Blueprint $table) {
            $table->string('aoc_certificate_no');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ncaa_acos', function (Blueprint $table) {
            $table->dropIfExists('aoc_certificate_no');
        });
    }
}
