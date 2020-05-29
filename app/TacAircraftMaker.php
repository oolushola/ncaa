<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TacAircraftMaker extends Model
{
    protected $table = 'tbl_ncaa_tac_aircraft_makers';
    protected $fillable = [
        'tac_id',
        'aircraft_maker_id',
        'aircraft_type_id'
    ];
}