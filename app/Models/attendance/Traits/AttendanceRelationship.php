<?php

namespace App\Models\attendance\Traits;

use App\Models\programme\Programme;
use App\Models\team_label\TeamLabel;

trait AttendanceRelationship
{
    public function team()
    {
        return $this->belongsTo(TeamLabel::class, 'team_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
