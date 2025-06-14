<?php

namespace Database\Factories;

use App\Enums\ContentType;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentVariationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'variation_id' => Variation::factory(),
            'content_key' => $this->faker->slug(2),
            'content_type' => $this->faker->randomElement(ContentType::cases()),
            'content_value' => [
                'en' => $this->faker->sentence,
                'fr' => $this->faker->sentence,
            ],
        ];
    }

    protected function getContentForType(ContentType $type): string
    {
        return match ($type) {
            ContentType::Text => fake()->sentence(),
            ContentType::Html => '<p>' . fake()->paragraph() . '</p>',
            ContentType::Markdown => '# ' . fake()->sentence() . "\n\n" . fake()->paragraph(),
        };
    }
} 