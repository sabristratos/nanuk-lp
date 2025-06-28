<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quote',
        'author_name',
        'company_name',
        'position',
        'rating',
        'is_active',
        'order',
        'language',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Scope a query to only include active testimonials.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include testimonials for a specific language.
     */
    public function scopeForLanguage(Builder $query, string $language): Builder
    {
        return $query->where('language', $language);
    }

    /**
     * Scope a query to order testimonials by their order field.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the display name for the testimonial.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->author_name && $this->company_name) {
            return "{$this->author_name} - {$this->company_name}";
        }
        
        if ($this->author_name) {
            return $this->author_name;
        }
        
        if ($this->company_name) {
            return $this->company_name;
        }
        
        return 'Client anonyme';
    }

    /**
     * Get the star rating as an array.
     */
    public function getStarsAttribute(): array
    {
        return array_fill(0, $this->rating, true);
    }
}
