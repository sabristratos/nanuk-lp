<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\Submission;
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

    public function getSubmissionsProperty()
    {
        $query = Submission::query()
            ->with(['experiment', 'variation']);

        if ($this->sortBy === 'experiment.name') {
            $query->join('experiments', 'submissions.experiment_id', '=', 'experiments.id')
                ->orderBy('experiments.name', $this->sortDirection)
                ->select('submissions.*');
        } elseif ($this->sortBy === 'variation.name') {
            $query->join('variations', 'submissions.variation_id', '=', 'variations.id')
                ->orderBy('variations.name', $this->sortDirection)
                ->select('submissions.*');
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate(15);
    }

    public function render(): View
    {
        return view('livewire.admin.submissions.index', [
            'submissions' => $this->submissions,
        ]);
    }
}
