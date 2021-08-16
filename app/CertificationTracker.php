<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CertificationTracker extends Model
{
    protected $table = "tbl_ncaa_certification_trackers";
    protected $fillable = [
        'certification_no',
        'date_assigned',
        'applicant_name',
        'certification_type',
        'cpm',
        'team_member',
        'start_date',
        'completion_date',
        'status',
        'aircraft_type',
        'created_by'
    ];
}
