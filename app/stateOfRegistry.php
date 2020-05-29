<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stateOfRegistry extends Model
{
    protected $table = 'tbl_ncaa_state_of_registries';
    protected $fillable = [
        'state_of_registry'
    ];
}
