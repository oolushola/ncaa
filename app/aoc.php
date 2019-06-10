<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class aoc extends Model
{
    protected $table = "tbl_ncaa_acos";
    protected $fillable=[
    	'aoc_holder',
        'aoc_certificate',
    	'issued_date',
    	'validity',
    	'ops_specs',
    	'part_g',
    	'remarks',
        'aoc_certificate_no',
        'created_by'
    ];
    protected $dates = ['issued_date', 'validity'];

    // public function scopePublished($query){
    //     $query->where('publish_on', '<=', Carbon::now());
    // }
    // public function scopeOldernews($query){
    //     $query->where('publish_on', '<', Carbon::now());
    // }
}
