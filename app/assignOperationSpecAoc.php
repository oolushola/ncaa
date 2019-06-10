<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class assignOperationSpecAoc extends Model
{
    protected $table = 'tbl_ncaa_assign_operation_spec_aocs';
    protected $fillable = [
        'aoc_holder_id',
        'operation_type_id'
    ];
}
