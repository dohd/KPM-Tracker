<?php

namespace App\Models\rating_scale;

use App\Models\ModelTrait;
use App\Models\rating_scale\Traits\RatingScaleAttribute;
use App\Models\rating_scale\Traits\RatingScaleRelationship;
use Illuminate\Database\Eloquent\Model;

class RatingScale extends Model
{
    use ModelTrait, RatingScaleAttribute, RatingScaleRelationship;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'rating_scales';

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->fill([
                'tid' => RatingScale::max('tid')+1,
                'user_id' => auth()->user()->id,
                'ins' => auth()->user()->ins,
            ]);
            return $instance;
        });

        static::addGlobalScope('ins', function ($builder) {
            // $builder->where('ins', auth()->user()->ins);
        });
    }
}
