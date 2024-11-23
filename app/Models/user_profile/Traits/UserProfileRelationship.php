<?php

namespace App\Models\user_profile\Traits;

use App\Models\company\Company;
use App\Models\User;

trait UserProfileRelationship
{
    public function user_login()
    {
        return $this->belongsTo(User::class, 'rel_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'ins');
    }
}
