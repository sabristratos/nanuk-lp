<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Experiments;

use App\Models\Experiment;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

#[Layout('components.layouts.admin')]
class ShowExperiment extends Component
{
    public Experiment $experiment;

    public function mount(Experiment $experiment): void
    {
        Gate::authorize('view-experiments');
        $this->experiment = $experiment->load(['variations', 'results']);
    }

    public function render(): View
    {
        // Data for the chart will be prepared here.
        $chartData = $this->prepareChartData();

        return view('livewire.admin.experiments.show-experiment', [
            'chartData' => $chartData,
        ]);
    }

    private function prepareChartData(): array
    {
        $results = $this->experiment->results()
            ->selectRaw('DATE(created_at) as date, variation_id, COUNT(*) as conversions')
            ->groupBy('date', 'variation_id')
            ->orderBy('date')
            ->get();

        // Determine start and end dates for the chart
        $startDate = $this->experiment->start_date;
        $endDate = $this->experiment->end_date?->isPast() ? $this->experiment->end_date : now();

        // If there's no start date on the experiment, try to infer from results.
        if (!$startDate && $results->isNotEmpty()) {
            $startDate = Carbon::parse($results->min('date'));
        }

        // If still no start date, we can't proceed.
        if (!$startDate) {
            return [];
        }

        $variations = $this->experiment->variations;
        $groupedResults = $results->groupBy('date');

        // Generate a complete, continuous date period
        $period = Carbon::parse($startDate)->startOfDay()->toPeriod($endDate->endOfDay());

        $chartData = collect($period)->map(function (Carbon $date) use ($variations, $groupedResults) {
            $dateString = $date->format('Y-m-d');
            $dataPoint = ['date' => $dateString];

            $dailyResults = $groupedResults->get($dateString, collect());

            foreach ($variations as $index => $variation) {
                /** @var \App\Models\Variation $variation */
                $field = 'variation_' . $index . '_conversions';
                $resultForVariation = $dailyResults->firstWhere('variation_id', $variation->id);
                $dataPoint[$field] = $resultForVariation ? $resultForVariation->conversions : 0;
            }

            return $dataPoint;
        });
        
        $colors = ['blue', 'red', 'green', 'yellow', 'purple', 'orange'];

        $this->js(<<<JS
            const variations = document.querySelectorAll('[data-variation]');
            const colors = {$this->experiment->variations->map(fn($v, $i) => $colors[$i % count($colors)])->toJson()};

            for (let i = 0; i < variations.length; i++) {
                const variation = variations[i];
                const color = colors[i];
                
                variation.style.setProperty('--tw-variation-color', 'var(--color-' + color + '-500)');
            }
        JS);

        return $chartData->values()->toArray();
    }
} 