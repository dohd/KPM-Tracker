<?php

namespace App\Models\team\Traits;

use App\Models\team\TeamMember;

trait VerifyMemberRelationship
{
    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }
}
