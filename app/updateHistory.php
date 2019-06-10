<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class updateHistory extends Model
{
    protected $table = 'tbl_ncaa_update_histories';
    protected $fillable = [
        'name',
        'module',
        'record_id',
        'actual',
    ];
}
