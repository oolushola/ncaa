<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class foreignAmoHolder extends Model
{
    protected $table = 'tbl_foreign_amo_holders';
    protected $fillable = [
        'foreign_amo_holder',
    ];
}
