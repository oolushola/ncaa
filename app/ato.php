<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ato extends Model
{
    protected $table = 'tbl_ncaa_atos';
    protected $fillable = [
        'training_organization_id',
        'approval_no',
        'ato_certificate',
        'date_of_first_issue',
        'date_of_renewal',
        'date_of_expiry',
        'created_by',
        'comments'
    ];
}
