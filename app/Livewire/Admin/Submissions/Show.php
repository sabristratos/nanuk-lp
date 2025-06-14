<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\ExperimentResult;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public ExperimentResult $result;

    public function mount(ExperimentResult $result): void
    {
        $this->result = $result->load(['experiment', 'variation']);
    }

    public function render()
    {
        return view('livewire.admin.submissions.show');
    }
}
