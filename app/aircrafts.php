<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class aircrafts extends Model
{
    protected $table= 'tbl_ncaa_aircrafts';
    protected $fillable = [
        'aoc_holder_id',
        'aircraft_maker_id',
        'registration_marks',
        'aircraft_type',
        'aircraft_serial_number',
        'year_of_manufacture',
        'registration_date',
        'registered_owner',
        'c_of_a_status',
        'c_of_a',
        'weight',
        'created_by',
    ];
}
