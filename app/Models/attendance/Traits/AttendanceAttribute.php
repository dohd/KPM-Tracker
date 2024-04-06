<?php

namespace App\Models\attendance\Traits;

trait AttendanceAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(null,
            // $this->getViewButtonAttribute('attendances.show', 'view-attendance'),
            $this->getEditButtonAttribute('attendances.edit', 'edit-attendance'),
            $this->getDeleteButtonAttribute('attendances.destroy', 'delete-attendance'),
        );
    }

    public function getGrantAmountAttribute()
    {
        return +$this->attributes['grant_amount'];
    }
}
