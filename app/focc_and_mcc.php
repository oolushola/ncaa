<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class focc_and_mcc extends Model
{
    protected $table = 'tbl_ncaa_focc_and_mccs';
    protected $fillable = [
        'operator_type',
        'operator',
        'focc_no',
        'mcc_no',
        'state_of_registry_id',
        'registered_owner',
        'aircraft_maker_id',
        'aircraft_type_id',
        'aircraft_reg_no_id',
        'date_of_first_issue',
        'renewal',
        'valid_till',
        'created_by',
        'amo_holder_status'
    ];
}
