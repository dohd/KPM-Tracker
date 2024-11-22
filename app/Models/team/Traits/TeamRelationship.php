<?php

namespace App\Models\team\Traits;

use App\Models\assign_score\AssignScore;
use App\Models\programme\Programme;
use App\Models\team\TeamSize;

trait TeamRelationship
{
    public function programmes()
    {
        return $this->hasManyThrough(Programme::class, AssignScore::class, 'team_id', 'id', 'id', 'programme_id');
    }

    public function assigned_scores()
    {
        return $this->hasMany(AssignScore::class, 'team_id');
    }

    public function team_sizes()
    {
        return $this->hasMany(TeamSize::class);
    }
}
