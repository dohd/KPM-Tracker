<?php

namespace App\Models\team\Traits;

trait TeamAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(null,
            $this->getEditButtonAttribute('teams.edit', 'edit-team-label'),
            $this->getDeleteButtonAttribute('teams.destroy', 'delete-team-label'),
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
        return '<span class="badge bg-'. ($this->is_active? 'success' : 'secondary') .' modal-btn" style="cursor:pointer;" role="button" data-bs-toggle="modal" data-bs-target="#status_modal" data-url="'. route('teams.update', $this) .'">'
        . $this->is_active_status . '<i class="bi bi-caret-down-fill"></i></span>';
    }
}
