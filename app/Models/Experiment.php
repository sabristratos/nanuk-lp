<?php

namespace App\Models;

use App\Enums\ExperimentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'status' => ExperimentStatus::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class);
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

    public function isActive(): bool
    {
        return $this->status === ExperimentStatus::Active
            && now()->between($this->start_date, $this->end_date);
    }
} 