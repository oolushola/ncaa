<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
    protected $table = "tbl_ncaa_team_members";
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'full_name',
        'created_by'
    ];
}
