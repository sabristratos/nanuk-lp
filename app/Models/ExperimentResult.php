<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'variation_id',
        'visitor_id',
        'conversion_type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
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