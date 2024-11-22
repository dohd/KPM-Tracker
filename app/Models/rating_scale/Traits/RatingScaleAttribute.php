<?php

namespace App\Models\rating_scale\Traits;

trait RatingScaleAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(null,
            $this->getEditButtonAttribute('score_cards.edit', 'edit-rating-scale'),
            $this->getDeleteButtonAttribute('score_cards.destroy', 'delete-rating-scale'),
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
        return '<span class="badge bg-'. ($this->is_active? 'success' : 'secondary') .' modal-btn" style="cursor:pointer;" role="button" data-bs-toggle="modal" data-bs-target="#status_modal" data-url="'. route('score_cards.update', $this) .'">'
        . $this->is_active_status . '<i class="bi bi-caret-down-fill"></i></span>';
    }

    /**
     * 
     */
    public function getScaleTypeTextAttribute()
    {
        switch ($this->scale_type) {
            case 'metric_size': return 'Metric Size';
            case 'generic_count': return 'Generic Count';
        }
    }
}
