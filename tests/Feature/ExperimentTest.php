<?php

namespace Tests\Feature;

use App\Enums\ContentType;
use App\Enums\ElementType;
use App\Enums\ExperimentStatus;
use App\Models\Experiment;
use App\Services\ExperimentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group ab-testing
 */
class ExperimentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_experiment_with_variations_via_service(): void
    {
        $service = app(ExperimentService::class);

        $data = [
            'name' => 'New Landing Page Test',
            'description' => 'A test to see which landing page converts better.',
            'status' => ExperimentStatus::Active->value,
            'variations' => [
                [
                    'name' => 'Control',
                    'is_control' => true,
                    'weight' => 50,
                    'configurations' => [
                        [
                            'element_key' => 'landing.hero',
                            'element_type' => ElementType::Layout->value,
                            'configuration' => json_encode(['css_classes' => 'bg-gray-100']),
                        ]
                    ],
                    'content' => [
                        [
                            'content_key' => 'hero.title',
                            'content_type' => ContentType::Html->value,
                            'content_value' => [
                                'en' => 'Welcome',
                                'fr' => 'Bienvenue',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $experiment = $service->createExperiment($data);

        $this->assertDatabaseHas('experiments', ['name' => 'New Landing Page Test']);
        $this->assertDatabaseHas('variations', ['name' => 'Control', 'experiment_id' => $experiment->id]);
        $this->assertDatabaseHas('experiment_configurations', ['element_key' => 'landing.hero']);
        $this->assertDatabaseHas('content_variations', ['content_key' => 'hero.title']);

        $content = Experiment::find($experiment->id)
            ->variations->first()
            ->contentVariations->first()
            ->getTranslation('content_value', 'fr');
            
        $this->assertEquals('Bienvenue', $content);
    }
} 