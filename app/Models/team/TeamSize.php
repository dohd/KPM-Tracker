<?php

namespace App\Models\team;

use App\Models\ModelTrait;
use App\Models\team\Traits\TeamSizeRelationship;
use Illuminate\Database\Eloquent\Model;

class TeamSize extends Model
{
    use ModelTrait, TeamSizeRelationship;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'team_sizes';

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
     * Guarded fields of model
     * @var array
     */
    protected $appends = ['is_editable'];


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
    }

    // custom attributes
    public function getIsEditableAttribute()
    {
        if ($this->in_score) {
            if (auth()->user()->user_type !== 'chair') {
                return false;
            }
        }
        return true;
    }
}

