<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CertificationType extends Model
{
    protected $table = 'tbl_ncaa_certification_types';
    protected $fillable = [
        'certification_type'
    ];
}
