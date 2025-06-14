<?php

namespace Database\Factories;

use App\Models\Experiment;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'experiment_id' => Experiment::factory(),
            'name' => fake()->words(2, true),
            'weight' => fake()->numberBetween(10, 90),
            'is_control' => false,
        ];
    }

    public function control(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_control' => true,
            'weight' => 50,
        ]);
    }
} 