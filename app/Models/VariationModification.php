<?php

namespace App\Models;

use App\Enums\ModificationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationModification extends Model
{
    use HasFactory;

    protected $fillable = [
        'variation_id',
        'type',
        'target',
        'payload',
    ];

    protected $casts = [
        'type' => ModificationType::class,
        'payload' => 'array',
    ];

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }
} 