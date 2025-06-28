<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\Submission;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public Submission $submission;

    public function mount(Submission $submission): void
    {
        $this->submission = $submission->load(['experiment', 'variation']);
    }

    public function render()
    {
        return view('livewire.admin.submissions.show');
    }
}
