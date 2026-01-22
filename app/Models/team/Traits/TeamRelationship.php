<?php

namespace App\Models\team\Traits;

use App\Models\assign_score\AssignScore;
use App\Models\metric\Metric;
use App\Models\programme\Programme;
use App\Models\team\TeamMember;
use App\Models\team\TeamSize;
use App\Models\team\VerifyMember;

trait TeamRelationship
{
    public function verify_members()
    {
        return $this->hasMany(VerifyMember::class);
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function teamSizesForPeriod($month, $year)
    {
        return $this->hasOne(TeamSize::class)
            ->whereYear('start_period', $year)
            ->whereMonth('start_period', $month)
            ->orderBy('start_period', 'ASC');
    }

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

    public function metrics()
    {
        return $this->hasMany(Metric::class)->withoutGlobalScopes();
    }
}
