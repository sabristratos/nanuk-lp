<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'experiment_id',
        'variation_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'website',
        'business_years',
        'main_objective',
        'online_advertising_experience',
        'monthly_budget',
        'ready_to_invest',
        'consent',
        'ip_address',
        'user_agent',
        'meta',
    ];

    protected $casts = [
        'consent' => 'boolean',
        'meta' => 'array',
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
