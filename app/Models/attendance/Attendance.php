<?php

namespace App\Models\attendance;

use App\Models\attendance\Traits\AttendanceAttribute;
use App\Models\attendance\Traits\AttendanceRelationship;
use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use ModelTrait, AttendanceAttribute, AttendanceRelationship;    

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'attendance';

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
                'user_id' => auth()->user()->id,
                'ins' => auth()->user()->ins,
            ]);
            return $instance;
        });

        static::addGlobalScope('team_id', function ($builder) {
            if (in_array(auth()->user()->user_type, ['captain', 'member'])) {
                $builder->where('team_id', auth()->user()->team_id);
            }
        });
    }
}
