<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class atol extends Model
{
    protected $table = 'tbl_ncaa_atols';
    protected $fillable = [
        'operator_type_checker',
        'operator_type',
        'licence_no',
        'atol_certificate',
        'date_of_first_issue',
        'renewal',
        'date_of_expiry',
        'created_by'
    ];
}
