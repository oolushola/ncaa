<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAircrafts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ncaa_aircrafts', function (Blueprint $table) {
            $table->string('cofr')->nullable()->after('weight');
            $table->string('cofa')->nullable()->after('cofr');
            $table->string('noise_cert')->nullable()->after('cofa');
            $table->string('mode_s')->nullable()->after('noise_cert');
            $table->boolean('rvsm')->nullable()->after('mode_s');
            $table->boolean('pbn')->nullable()->after('rvsm');
            $table->boolean('lvo')->nullable()->after('pbn');
            $table->boolean('abs_b')->nullable()->after('lvo');
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
            $table->dropColumn([
                'cofr',
                'cofa',
                'noise_cert',
                'mode_s',
                'rvsm',
                'pbn',
                'lvo',
                'abs_b'
            ]);
        });
    }
}
