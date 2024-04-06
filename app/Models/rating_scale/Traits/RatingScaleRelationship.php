<?php

namespace App\Models\rating_scale\Traits;

use App\Models\rating_scale\RatingScaleItem;

trait RatingScaleRelationship
{
    public function items()
    {
        return $this->hasMany(RatingScaleItem::class);
    }
}
