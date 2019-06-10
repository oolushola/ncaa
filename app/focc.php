<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class focc extends Model
{
    protected $table = "tbl_ncaa_foccs";
    protected $fillable = [
        'aoc_holder_id',
        'focc_no',
        'aircraft_type',
        'aircraft_reg_no',
        'type_of_operations',
        'date_of_first_issue',
        'renewal',
        'valid_till',
        'approval_import',
        'inspector',
        'created_by',
    ];
}
