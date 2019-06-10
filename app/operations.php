<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class operations extends Model
{
    protected $table='tbl_ncaa_operations';
    protected $fillable = [
        'operation_type'
    ];
}
