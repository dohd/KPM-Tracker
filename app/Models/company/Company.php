<?php

namespace App\Models\company;

use App\Models\ModelTrait;
use App\Models\company\Traits\CompanyAttribute;
use App\Models\company\Traits\CompanyRelationship;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use ModelTrait, CompanyAttribute, CompanyRelationship;    

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'companies';

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
            return $instance;
        });
    }
}
