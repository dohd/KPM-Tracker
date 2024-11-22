<?php

namespace App\Models\team_label;

use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class TeamSize extends Model
{
    use ModelTrait;

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
}
