<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicantName extends Model
{
    protected $table = 'tbl_ncaa_applicant_names';
    protected $fillable = [
        'applicant_name'
    ];
}
