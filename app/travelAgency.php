<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class travelAgency extends Model
{
    protected $table = 'tbl_ncaa_travel_agencies';
    protected $fillable = [
        'travel_agency_name',
        'description'
    ];
}
