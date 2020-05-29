<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class aop extends Model
{
    protected $table= 'tbl_ncaa_aops';
    protected $fillable = [
        'operator',
        'operator_type',
        'licence_no',
        'aop_certificate',
        'date_of_first_issue',
        'date_of_renewal',
        'date_of_expiry',
        'comment',
        'created_by'
    ];
}