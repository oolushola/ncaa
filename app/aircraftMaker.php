<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class aircraftMaker extends Model
{
    protected $table = "tbl_ncaa_aircraft_makers";
    protected $fillable=[
    	'aircraft_maker'
    ];
}
