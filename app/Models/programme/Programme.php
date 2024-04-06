<?php

namespace App\Models\programme;

use App\Models\ModelTrait;
use App\Models\programme\Traits\ProgrammeAttribute;
use App\Models\programme\Traits\ProgrammeRelationship;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use ModelTrait, ProgrammeAttribute, ProgrammeRelationship;    

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'programmes';

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
                'tid' => Programme::max('tid')+1,
                'user_id' => auth()->user()->id,
                'ins' => auth()->user()->ins,
            ]);
            return $instance;
        });

        static::addGlobalScope('ins', function ($builder) {
            // $builder->where('ins', '=', auth()->user()->ins);
        });
    }
}
