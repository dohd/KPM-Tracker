<?php

namespace App\Models\metric\Traits;

trait MetricAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(null,
            // $this->getViewButtonAttribute('metrics.show', 'view-metric'),
            $this->getEditButtonAttribute('metrics.edit', 'edit-metric'),
            $this->getDeleteButtonAttribute('metrics.destroy', 'delete-metric'),
        );
    }

    public function getGrantAmountAttribute()
    {
        return +$this->attributes['grant_amount'];
    }
}
