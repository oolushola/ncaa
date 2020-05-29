<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class foreignRegistrationMarks extends Model
{
    protected $table = 'tbl_ncaa_foreign_registration_marks';
    protected $fillable = [
        'foreign_registration_marks'
    ];
}
