<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paas extends Model
{
    protected $table= 'tbl_ncaa_paas';
    protected $fillable = [
        'operator_type',
        'operator',
        'licence_no',
        'paas_certificate',
        'date_of_first_issue',
        'date_of_renewal',
        'date_of_expiry',
        'created_by'
    ];
}
