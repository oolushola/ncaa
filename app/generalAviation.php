<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class generalAviation extends Model
{
    protected $table = 'tbl_ncaa_general_aviations';
    protected $fillable = [
        'general_aviation_name'
    ];
}
