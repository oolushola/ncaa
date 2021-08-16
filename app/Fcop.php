<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fcop extends Model
{
    protected $table="tbl_ncaa_fcops";
    protected $fillable = [
        "foreign_airline",
        "licence_no",
        "fcop_certificate",
        "part_18",
        "part_10",
        "part_17",
        "fcop_status",
        "date_fcop_issued",
        "comments",
        "created_by"
    ];
}
