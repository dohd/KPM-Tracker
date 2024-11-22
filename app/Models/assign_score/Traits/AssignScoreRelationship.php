<?php

namespace App\Models\assign_score\Traits;

use App\Models\programme\Programme;
use App\Models\team\Team;

trait AssignScoreRelationship
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
