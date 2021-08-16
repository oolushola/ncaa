<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class foreignAirline extends Model
{
    protected $table="tbl_ncaa_foreign_airlines";
    protected $fillable = [
        "foreign_airline"
    ];
}
