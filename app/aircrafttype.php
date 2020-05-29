<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class aircrafttype extends Model
{
    protected $table = 'tbl_ncaa_aircraft_types';
    protected $fillable = [
        'aircraft_maker_id',
        'aircraft_type'
    ];
}
