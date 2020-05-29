<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class foreignAmoRatings extends Model
{
    protected $table = 'tbl_ncaa_foreign_amo_ratings';
    protected $fillable = [
        'foreign_amo_id',
        'aircraft_maker_id',
        'aircraft_type_id'
    ];
}
