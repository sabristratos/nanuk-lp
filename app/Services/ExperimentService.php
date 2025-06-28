<?php

namespace App\Services;

use App\Enums\ExperimentStatus;
use App\Models\Experiment;
use App\Models\ExperimentView;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Enums\ElementType;
use App\Enums\ContentType;
use Illuminate\Support\Str;

class ExperimentService
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get an active experiment by its ID
     */
    public function getActiveExperiment(int $experimentId): ?Experiment
    {
        $cacheKey = 'experiment.active.id.' . $experimentId;

        $experiment = Cache::remember($cacheKey, now()->addHours(1), function () use ($experimentId) {
            // For preview mode, we don't need to check if experiment is active
            // This allows previewing of draft/inactive experiments
            $query = Experiment::where('id', $experimentId)
                ->with(['variations.modifications']);

            return $query->first();
        });

        return $experiment;
    }

    /**
     * Get an active experiment by target key (for route-based assignment)
     */
    public function getActiveExperimentByTarget(string $targetKey): ?Experiment
    {
        $cacheKey = 'experiment.active.' . $targetKey;

        $experiment = Cache::remember($cacheKey, now()->addHours(24), function () use ($targetKey) {
            $query = Experiment::where('status', ExperimentStatus::Active)
                ->where(function (Builder $query) {
                    $query->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function (Builder $query) {
                    $query->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->whereHas('variations.modifications', function (Builder $query) use ($targetKey) {
                    $query->where('target', 'like', $targetKey . '%');
                })
                ->with(['variations.modifications']);

            return $query->first();
        });

        return $experiment;
    }

    public function getAssignedVariation(int $experimentId): ?Variation
    {
        $cookieName = "experiment_{$experimentId}_variation";
        
        if (Cookie::has($cookieName)) {
            $variationId = Cookie::get($cookieName);
            return Variation::with(['modifications', 'experiment'])->find($variationId);
        }

        $experiment = Experiment::with('variations')->find($experimentId);
        if (!$experiment) {
            return null;
        }

        $variation = $this->assignVariation($experiment);

        if ($variation) {
            $this->recordView($variation);
            Cookie::queue($cookieName, $variation->id, $experiment->end_date ? $experiment->end_date->diffInMinutes() : 43200);
        }

        return $variation;
    }

    protected function assignVariation(Experiment $experiment): ?Variation
    {
        $rand = mt_rand(1, 100);
        $cumulativeWeight = 0;

        foreach ($experiment->variations as $variation) {
            /** @var Variation $variation */
            $cumulativeWeight += $variation->weight;
            if ($rand <= $cumulativeWeight) {
                return $variation;
            }
        }

        /** @var Variation|null $firstVariation */
        $firstVariation = $experiment->variations->first();
        return $firstVariation;
    }

    protected function recordView(Variation $variation): void
    {
        $visitorCookieName = 'nanuk_visitor_id';

        $visitorId = Cookie::get($visitorCookieName);
        if (!$visitorId) {
            $visitorId = Str::uuid()->toString();
            // Store the cookie for a year, making the visitor ID persistent.
            Cookie::queue($visitorCookieName, $visitorId, 60 * 24 * 365);
        }

        // Check if a view for this visitor already exists for this experiment
        /** @var Experiment $experiment */
        $experiment = $variation->experiment;
        $existingView = $experiment->views()
            ->where('visitor_id', $visitorId)
            ->exists();

        if (!$existingView) {
            ExperimentView::create([
                'experiment_id' => $variation->experiment_id,
                'variation_id' => $variation->id,
                'visitor_id' => $visitorId,
            ]);
            
            // Dispatch an event so external services can hook into this.
            \App\Events\VariationAssigned::dispatch(
                $experiment,
                $variation,
                $visitorId
            );
        }
    }
} 