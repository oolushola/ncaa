<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForeignAirlineDacl extends Model
{
    protected $table = "tbl_ncaa_foreign_airline_dacls";
    protected $fillable = [
        "airline_name",
        "dacl_no",
        "dacl_certificate",
        "dacl_issue_date",
        "aoc_opspec",
        "aoc_expiry_date",
        "country",
        "remarks",
        "created_by"
    ];
}