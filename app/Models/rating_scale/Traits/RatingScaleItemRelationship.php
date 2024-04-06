<?php

namespace App\Models\rating_scale\Traits;

use App\Models\rating_scale\RatingScale;

trait RatingScaleItemRelationship
{
    public function rating_scale()
    {
        return $this->belongsTo(RatingScale::class);
    }
}
