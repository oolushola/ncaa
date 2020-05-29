<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class localamo extends Model
{
    protected $table = 'tbl_ncaa_amo_locals';
    protected $fillable = [
        'holder_criteria',
        'aoc_holder_id',
        'amo_approval_number',
        'amo_approval_number_file',
        'ratings_capabilities',
        'maintenance_locations',
        'expiry',
        'amo_pm_aprvl_pg_lep',
        'amo_pm_aprvl_pg_lep_file',
        'extention',
        'created_by',
        'aircraft_maker_id',
        'aircraft_type_id'
    ];
}
