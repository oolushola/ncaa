<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cpm extends Model
{
    protected $table = "tbl_ncaa_cpms";
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'full_name'
    ];
}
