<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class atl extends Model
{
    protected $table= 'tbl_ncaa_atls';
    protected $fillable = [
        'operator_type',
        'operator',
        'licence_no',
        'atl_certificate',
        'date_of_first_issue',
        'date_of_renewal',
        'date_of_expiry',
        'comment',
        'created_by',
        'comments'
    ];
}
