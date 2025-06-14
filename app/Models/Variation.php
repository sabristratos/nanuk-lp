<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'name',
        'description',
        'weight',
        'is_control',
    ];

    protected $casts = [
        'is_control' => 'boolean',
        'weight' => 'integer',
    ];

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    public function modifications(): HasMany
    {
        return $this->hasMany(VariationModification::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExperimentResult::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ExperimentView::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ExperimentMetric::class);
    }
} 