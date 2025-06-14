<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $variation_id
 * @property string $element_selector
 * @property array $content_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ContentVariation extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'variation_id',
        'element_selector', 
        'content_value',
    ];

    protected array $translatable = ['content_value'];

    protected $casts = [
        'content_value' => 'array',
    ];

    /**
     * Get the variation that owns this content variation.
     *
     * @return BelongsTo<Variation, $this>
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }
} 