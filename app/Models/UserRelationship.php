<?php

namespace App\Models;

use App\Models\company\Company;

trait UserRelationship
{
    public function company()
    {
        return $this->belongsTo(Company::class, 'ins');
    }
}
