<?php

namespace App\Models\programme\Traits;

use App\Models\assign_score\AssignScore;

trait ProgrammeRelationship
{
    public function assignScores()
    {
        return $this->hasMany(AssignScore::class);
    }
}
