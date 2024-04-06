<?php

namespace App\Models\file_import;

use App\Models\file_import\Traits\FileImportAttribute;
use App\Models\file_import\Traits\FileImportRelationship;
use App\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class FileImport extends Model
{
    use ModelTrait, FileImportAttribute, FileImportRelationship;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'file_imports';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'category',
        'category_dir',
        'origin_name',
        'file_name',
        'date',
        'user_id', 
        'ins',
    ];

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

        static::addGlobalScope('ins', function ($builder) {
            $builder->where('ins', auth()->user()->ins);
        });
    }
}
