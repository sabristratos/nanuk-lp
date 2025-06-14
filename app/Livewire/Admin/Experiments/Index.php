<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Experiments;

use App\Models\Experiment;
use App\Enums\ExperimentStatus;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    public ?string $status = null;

    public bool $confirmingDelete = false;
    public ?Experiment $deletingExperiment = null;

    public function hasFilters(): bool
    {
        return !empty($this->search) || !empty($this->status);
    }

    #[On('experiment-saved')]
    public function refresh(): void
    {
        // This will refresh the component rendering the experiment list.
    }

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

    #[On('confirm-delete-experiment')]
    public function confirmDeleteExperiment(Experiment $experiment): void
    {
        Gate::authorize('delete-experiments');
        $this->deletingExperiment = $experiment;
        $this->confirmingDelete = true;
    }

    public function delete(): void
    {
        Gate::authorize('delete-experiments');
        if (!$this->deletingExperiment) {
            return;
        }

        try {
            $this->deletingExperiment->delete();
            Flux::toast(
                text: __('Experiment deleted successfully.'),
                heading: __('Success'),
                variant: 'success'
            );
            $this->dispatch('experiment-saved'); // Re-use event to refresh list
        } catch (\Exception $e) {
            Log::error('Failed to delete experiment: ' . $e->getMessage());
            Flux::toast(
                text: __('Failed to delete experiment. Please try again.'),
                heading: __('Error'),
                variant: 'danger'
            );
        }

        $this->confirmingDelete = false;
        $this->deletingExperiment = null;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $experiments = Experiment::query()
            ->withCount('variations')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, fn ($query, $status) => $query->where('status', $status))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.experiments.index', [
            'experiments' => $experiments,
            'statuses' => ExperimentStatus::cases(),
        ]);
    }
} 