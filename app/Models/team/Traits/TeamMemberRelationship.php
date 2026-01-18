<?php

namespace App\Models\team\Traits;

use App\Models\team\VerifyMember;

trait TeamMemberRelationship
{
    public function verify_members()
    {
        return $this->hasMany(VerifyMember::class);
    }
}
