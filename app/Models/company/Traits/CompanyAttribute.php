<?php

namespace App\Models\company\Traits;

trait CompanyAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(
            $this->getViewButtonAttribute('companies.show', ''),
            $this->getEditButtonAttribute('companies.edit', ''),
            $this->getDeleteButtonAttribute('companies.destroy', ''),
        );
    }
}
