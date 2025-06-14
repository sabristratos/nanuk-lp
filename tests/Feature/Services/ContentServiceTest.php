<?php

namespace Tests\Feature\Services;

use App\Models\Experiment;
use App\Models\Variation;
use App\Services\ContentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * @group ab-testing
 */
class ContentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ContentService $contentService;
    protected Variation $variation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentService = $this->app->make(ContentService::class);
        $this->seed(\Database\Seeders\ExperimentSeeder::class);
        
        $experiment = Experiment::where('name', 'Hero Section Test')->first();
        $this->variation = $experiment->variations()->where('is_control', false)->first(); // Use alternative variation
    }

    public function test_can_get_all_content_for_a_variation(): void
    {
        $content = $this->contentService->getContent($this->variation->id);
        
        $this->assertIsArray($content);
        $this->assertArrayHasKey('hero.title', $content);
        $this->assertArrayHasKey('hero.subtitle', $content);
        $this->assertEquals('<span class="block">VOTRE PROCHAIN SUCCÈS</span><span class="block text-primary-400 mt-1 md:mt-2">PUBLICITAIRE COMMENCE ICI</span>', $content['hero.title']);
    }

    public function test_can_get_a_specific_content_item_by_key(): void
    {
        $title = $this->contentService->get($this->variation->id, 'hero.title');
        
        $this->assertEquals('<span class="block">VOTRE PROCHAIN SUCCÈS</span><span class="block text-primary-400 mt-1 md:mt-2">PUBLICITAIRE COMMENCE ICI</span>', $title);
    }
    
    public function test_it_uses_spatie_translation_fallback_correctly(): void
    {
        app()->setLocale('en');
        $title = $this->contentService->get($this->variation->id, 'hero.title');
        $this->assertEquals('<span class="block">YOUR NEXT ADVERTISING</span><span class="block text-primary-400 mt-1 md:mt-2">SUCCESS STARTS HERE</span>', $title);
        
        // Assuming 'de' is not a stored language, it should fallback to 'en' (or your config's fallback)
        config(['app.fallback_locale' => 'en']);
        app()->setLocale('de');
        $fallbackTitle = $this->contentService->get($this->variation->id, 'hero.title');
        $this->assertEquals('<span class="block">YOUR NEXT ADVERTISING</span><span class="block text-primary-400 mt-1 md:mt-2">SUCCESS STARTS HERE</span>', $fallbackTitle);
    }

    public function test_content_retrieval_is_cached(): void
    {
        $cacheKey = "content.item.{$this->variation->id}.hero.title";
        
        // Ensure cache is empty before the first call
        Cache::forget($cacheKey);
        $this->assertFalse(Cache::has($cacheKey));

        // First call, should cache the result
        $this->contentService->get($this->variation->id, 'hero.title');
        $this->assertTrue(Cache::has($cacheKey));

        // Mock the cached value to ensure the service reads from cache
        Cache::put($cacheKey, 'cached-value', 60);
        $cachedResult = $this->contentService->get($this->variation->id, 'hero.title');
        $this->assertEquals('cached-value', $cachedResult);
    }
} 