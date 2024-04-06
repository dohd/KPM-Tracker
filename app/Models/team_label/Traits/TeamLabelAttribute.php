<?php

namespace App\Models\team_label\Traits;

trait TeamLabelAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(null,
            $this->getEditButtonAttribute('team_labels.edit', 'edit-team-label'),
            $this->getDeleteButtonAttribute('team_labels.destroy', 'delete-team-label'),
        );
    }

    /**
     * Is active status
     */
    public function getIsActiveStatusAttribute()
    {
        return $this->is_active? 'Active' : 'Inactive';
    }

    /**
     * Is active status budge
     */
    public function getIsActiveStatusBudgeAttribute()
    {
        return '<span class="badge bg-'. ($this->is_active? 'success' : 'secondary') .' modal-btn" style="cursor:pointer;" role="button" data-bs-toggle="modal" data-bs-target="#status_modal" data-url="'. route('team_labels.update', $this) .'">'
        . $this->is_active_status . '<i class="bi bi-caret-down-fill"></i></span>';
    }
}
