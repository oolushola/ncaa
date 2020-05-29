<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pncf extends Model
{
    protected $table = 'tbl_ncaa_pncfs';
    protected $fillable = [
        'operator_type',
        'operator',
        'licence_no',
        'pncf_certificate',
        'date_of_first_issue',
        'date_of_renewal',
        'date_of_expiry',
        'created_by'
    ];
}
