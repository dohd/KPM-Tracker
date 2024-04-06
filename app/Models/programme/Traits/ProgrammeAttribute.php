<?php

namespace App\Models\programme\Traits;

trait ProgrammeAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(
            null,
            $this->getEditButtonAttribute('programmes.edit', 'edit-programme'),
            $this->getDeleteButtonAttribute('programmes.destroy', 'delete-programme'),
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
        return '<span class="badge bg-'. ($this->is_active? 'success' : 'secondary') .' modal-btn" style="cursor:pointer;" role="button" data-bs-toggle="modal" data-bs-target="#status_modal" data-url="'. route('programmes.update', $this) .'">'
        . $this->is_active_status . '<i class="bi bi-caret-down-fill"></i></span>';
    }

    
    public function getTargetAmountAttribute()
    {
        return +$this->attributes['target_amount'];
    }
    public function getAmountPercAttribute()
    {
        return +$this->attributes['amount_perc'];
    }
    public function getEveryAmountPercAttribute()
    {
        return +$this->attributes['every_amount_perc'];
    }
    public function getAboveAmountPercAttribute()
    {
        return +$this->attributes['above_amount_perc'];
    }
}
