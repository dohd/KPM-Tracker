<?php

namespace App\Models;

use App\Models\company\Company;
use App\Models\team\Team;

trait UserRelationship
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'ins');
    }
}
