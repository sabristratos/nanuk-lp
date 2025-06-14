<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'variation_id',
        'metric_name',
        'metric_value',
        'recorded_at',
    ];

    protected $casts = [
        'metric_value' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }
} 