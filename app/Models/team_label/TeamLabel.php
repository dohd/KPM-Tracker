<?php

namespace App\Models\team_label;

use App\Models\ModelTrait;
use App\Models\team_label\Traits\TeamLabelAttribute;
use App\Models\team_label\Traits\TeamLabelRelationship;
use Illuminate\Database\Eloquent\Model;

class TeamLabel extends Model
{
    use ModelTrait, TeamLabelAttribute, TeamLabelRelationship;    

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'teams';

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
            $instance->user_id = auth()->user()->id;
            $instance->ins = auth()->user()->ins;
            return $instance;
        });

        static::addGlobalScope('id', function ($builder) {
            if (in_array(auth()->user()->user_type, ['captain', 'member'])) {
                $builder->where('id', auth()->user()->team_id);
            }
        });
    }
}
