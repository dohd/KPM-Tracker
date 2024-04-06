<?php

namespace App\Models\rating_scale;

use App\Models\rating_scale\Traits\RatingScaleItemRelationship;
use Illuminate\Database\Eloquent\Model;

class RatingScaleItem extends Model
{
    use RatingScaleItemRelationship;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'rating_scale_items';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [];

    /**
     * Default values for model fields
     * @var array
     */
    protected $attributes = [];

    /**
     * Dates
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Guarded fields of model
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Constructor of Model
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
