<?php

namespace App\Models\file_import\Traits;

trait FileImportAttribute
{
    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getButtonWrapperAttribute(
            $this->getViewButtonAttribute('file_imports.show', ''), 
            null,
            $this->getDeleteButtonAttribute('file_imports.destroy', ''),
        );
    }
}
