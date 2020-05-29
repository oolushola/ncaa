<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trainingOrganization extends Model
{
    protected $table = 'tbl_ncaa_training_organizations';
    protected $fillable = [
        'training_organization',
        'description'
    ];
}
