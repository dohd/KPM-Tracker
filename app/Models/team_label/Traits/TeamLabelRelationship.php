<?php

namespace App\Models\team_label\Traits;

use App\Models\assign_score\AssignScore;
use App\Models\programme\Programme;

trait TeamLabelRelationship
{
    public function programmes()
    {
        return $this->hasManyThrough(Programme::class, AssignScore::class, 'team_id', 'id', 'id', 'programme_id');
    }

    public function assigned_scores()
    {
        return $this->hasMany(AssignScore::class, 'team_id');
    }
}
