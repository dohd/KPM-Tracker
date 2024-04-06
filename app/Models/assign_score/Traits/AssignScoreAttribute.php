<?php

namespace App\Models\assign_score\Traits;

trait AssignScoreAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(null,
            $this->getEditButtonAttribute('assign_scores.edit', 'edit-assign-score'),
            $this->getDeleteButtonAttribute('assign_scores.destroy', 'delete-assign-score'),
        );
    }
}
