<?php

namespace App\Models\metric\Traits;

use App\Models\metric\MetricMember;
use App\Models\programme\Programme;
use App\Models\team\Team;

trait MetricRelationship
{
    public function metricMembers()
    {
        return $this->hasMany(MetricMember::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id')->withoutGlobalScopes();
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
