<?php

namespace Database\Factories;

use App\Enums\ElementType;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperimentConfigurationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'variation_id' => Variation::factory(),
            'element_key' => fake()->word(),
            'element_type' => fake()->randomElement(ElementType::cases())->value,
            'configuration' => $this->getConfigurationForType(fake()->randomElement(ElementType::cases())),
        ];
    }

    protected function getConfigurationForType(ElementType $type): array
    {
        return match ($type) {
            ElementType::Color => [
                'color' => fake()->hexColor(),
                'opacity' => fake()->randomFloat(2, 0, 1),
            ],
            ElementType::Text => [
                'text' => fake()->sentence(),
                'style' => [
                    'fontSize' => fake()->numberBetween(12, 24) . 'px',
                    'fontWeight' => fake()->randomElement(['normal', 'bold']),
                ],
            ],
            ElementType::Visibility => [
                'visible' => fake()->boolean(),
                'display' => fake()->randomElement(['block', 'none']),
            ],
            ElementType::Position => [
                'position' => fake()->randomElement(['top', 'bottom', 'left', 'right']),
                'offset' => fake()->numberBetween(0, 100) . 'px',
            ],
            ElementType::Layout => [
                'layout' => fake()->randomElement(['grid', 'flex']),
                'columns' => fake()->numberBetween(1, 4),
                'gap' => fake()->numberBetween(10, 50) . 'px',
            ],
        };
    }
} 