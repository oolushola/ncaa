<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class aocAircrafts extends Model
{
    protected $table = 'tbl_ncaa_aoc_aircrafts';
    protected $fillable = [
        'aoc_holder_id',
        'aircraft_maker_id'
    ];
}
