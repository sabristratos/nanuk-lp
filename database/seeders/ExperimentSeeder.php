<?php

namespace Database\Seeders;

use App\Enums\ExperimentStatus;
use App\Enums\ModificationType;
use App\Models\Experiment;
use Illuminate\Database\Seeder;

class ExperimentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean up old experiments to avoid confusion
        Experiment::query()->delete();

        $experiment = Experiment::factory()->create([
            'name' => 'Homepage Hero Test (Unified)',
            'description' => 'Testing different headlines and background colors on the hero section.',
            'status' => ExperimentStatus::Active,
            'start_date' => now()->subDay(),
            'end_date' => now()->addWeeks(2),
        ]);

        // --- Control Variation (No changes) ---
        $control = $experiment->variations()->create([
            'name' => 'Control',
            'description' => 'The original version.',
            'weight' => 50,
        ]);

        // --- Variation A (New Headline and Background) ---
        $variationA = $experiment->variations()->create([
            'name' => 'Variation A',
            'description' => 'More direct copy and a darker background.',
            'weight' => 50,
        ]);

        // Modification 1: Change the title text
        $variationA->modifications()->create([
            'type' => ModificationType::Text,
            'target' => 'hero.title',
            'payload' => [
                'multilang_content' => [
                    'en' => 'The Future of Web Development is Here.',
                    'fr' => 'L\'avenir du développement Web est là.',
                ]
            ],
        ]);
        
        // Modification 2: Change the background color of the hero section
        $variationA->modifications()->create([
            'type' => ModificationType::Style,
            'target' => 'hero.section', // Matches the data-element-key in the blade file
            'payload' => [
                'property' => 'backgroundColor',
                'value' => '#1a202c', // Dark gray
            ],
        ]);
    }
} 