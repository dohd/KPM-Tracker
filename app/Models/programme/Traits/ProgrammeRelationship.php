<?php

namespace App\Models\programme\Traits;

use App\Models\assign_score\AssignScore;
use App\Models\metric\Metric;
use App\Models\programme\Programme;

trait ProgrammeRelationship
{
    public function parent()
    {
        return $this->belongsTo(Programme::class, 'cumulative_programme_id')
            ->with('parent');
    }

    public function assignScores()
    {
        return $this->hasMany(AssignScore::class);
    }
    
    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }
}
