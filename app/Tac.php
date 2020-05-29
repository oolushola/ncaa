<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tac extends Model
{
    protected $table = 'tbl_ncaa_tacs';
    protected $fillable = [
        'aircraft_maker_id',
        'tc_acceptance_approval',
        'certificate_no',
        'date_issued',
        'tc_holder',
        'original_tc_issued_by',
        'tc_no',
        'tcds_latest_revision',
        'remarks'
    ];
}
