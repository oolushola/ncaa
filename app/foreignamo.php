<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class foreignamo extends Model
{
    protected $table = "tbl_ncaa_foreign_amos";
    protected $fillable = [
        'amo_holder',
        'regional_country_id',
        'moe_reference',
        'approvals',
        'ratings_capabilities',
        'amo_number',
        'amo',
        'expiry',
        'created_by'
    ];
}
