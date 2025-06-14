<?php

namespace Database\Factories;

use App\Enums\ExperimentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperimentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(ExperimentStatus::cases())->value,
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
        ];
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExperimentStatus::Draft->value,
            'start_date' => null,
            'end_date' => null,
        ]);
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExperimentStatus::Active->value,
            'start_date' => now(),
            'end_date' => now()->addMonths(2),
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExperimentStatus::Completed->value,
            'start_date' => now()->subMonths(2),
            'end_date' => now()->subDays(1),
        ]);
    }
} 