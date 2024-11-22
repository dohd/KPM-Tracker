<?php

namespace App\Models\team\Traits;

use App\Models\team\Team;

trait TeamSizeRelationship
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
