<?php

namespace App\Models\programme\Traits;

use App\Models\action_plan\ActionPlan;
use App\Models\participant_list\ParticipantList;

trait ProgrammeRelationship
{
    public function participant_lists()
    {
        return $this->hasManyThrough(ParticipantList::class, ActionPlan::class, 'programme_id', 'action_plan_id', 'id', 'id');
    }

    public function action_plans()
    {
        return $this->hasMany(ActionPlan::class);
    }
}
