<?php

namespace App\Models\programme\Traits;

use App\Models\assign_score\AssignScore;
use App\Models\metric\Metric;

trait ProgrammeRelationship
{
    public function assignScores()
    {
        return $this->hasMany(AssignScore::class);
    }
    
    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }
}
