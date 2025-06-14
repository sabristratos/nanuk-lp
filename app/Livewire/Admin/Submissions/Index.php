<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\ExperimentResult;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $column;
        $this->resetPage();
    }

    public function getResultsProperty()
    {
        $query = ExperimentResult::query()
            ->with(['experiment', 'variation']);

        if ($this->sortBy === 'experiment.name') {
            $query->whereHas('experiment')
                ->orderBy(
                    \App\Models\Experiment::select('name')
                        ->whereColumn('experiments.id', 'experiment_results.experiment_id'),
                    $this->sortDirection
                );
        } elseif ($this->sortBy === 'variation.name') {
            $query->whereHas('variation')
                ->orderBy(
                    \App\Models\Variation::select('name')
                        ->whereColumn('variations.id', 'experiment_results.variation_id'),
                    $this->sortDirection
                );
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate(15);
    }

    public function render(): View
    {
        return view('livewire.admin.submissions.index', [
            'results' => $this->results,
        ]);
    }
}
