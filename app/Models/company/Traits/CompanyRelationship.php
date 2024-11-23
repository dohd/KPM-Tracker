<?php

namespace App\Models\company\Traits;

use App\Models\User;

trait CompanyRelationship
{
    public function users()
    {
        return $this->hasMany(User::class, 'ins');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'ins');
    }
}
