<?php

namespace App\Livewire;

use App\Models\Experiment;
use App\Models\Variation;
use App\Services\MetricsService;
use App\Services\SubmissionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Validate;

class LandingPageForm extends Component
{
    public bool $submissionFailed = false;
    public bool $submissionSuccess = false;
    public string $failureMessage = '';
    public string $displayMode = 'modal';
    
    public ?int $experimentId = null;
    public ?int $variationId = null;

    #[Validate('required|string|max:255')]
    public string $firstName = '';

    #[Validate('required|string|max:255')]
    public string $lastName = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|url|max:255')]
    public string $website = '';

    #[Validate('required|string')]
    public string $businessYears = '';

    #[Validate('required|string')]
    public string $mainObjective = '';

    #[Validate('required|string')]
    public string $onlineAdvertisingExperience = '';

    #[Validate('required|string')]
    public string $monthlyBudget = '';

    #[Validate('required|string')]
    public string $readyToInvest = '';

    #[Validate('accepted')]
    public bool $consent = false;

    protected $listeners = ['openModal'];

    public function mount(?int $experimentId = null, ?int $variationId = null, string $displayMode = 'modal'): void
    {
        $this->experimentId = $experimentId;
        $this->variationId = $variationId;
        $this->displayMode = $displayMode;

        if ($this->experimentId && $this->variationId) {
            $experiment = \App\Models\Experiment::find($this->experimentId);
            if ($experiment) {
                $variation = $experiment->variations()->find($this->variationId);
                if ($variation) {
                    $formModification = $variation->modifications->firstWhere('type', 'component');
                    if ($formModification) {
                        $this->displayMode = $formModification->payload['show'] ?? 'modal';
                    }
                }
            }
        }

        // Auto-fill form with browser data if available
        $this->autoFillForm();
    }

    public function autoFillForm(): void
    {
        // This will be handled by JavaScript to auto-fill form fields
        // The actual auto-fill logic will be in the Blade template
    }

    public function openModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->submissionFailed = false;
        $this->submissionSuccess = false;
        $this->consent = false;
        
        // Use Flux UI modal control
        $this->modal('landing-form')->show();
    }

    public function closeModal(): void
    {
        // Use Flux UI modal control
        $this->modal('landing-form')->close();
    }

    public function resetForm(): void
    {
        $this->firstName = '';
        $this->lastName = '';
        $this->email = '';
        $this->phone = '';
        $this->website = '';
        $this->businessYears = '';
        $this->mainObjective = '';
        $this->onlineAdvertisingExperience = '';
        $this->monthlyBudget = '';
        $this->readyToInvest = '';
        $this->consent = false;
    }

    public function submit(SubmissionService $submissionService)
    {
        $this->validate();

        $this->submissionFailed = false;
        $this->submissionSuccess = false;
        $this->failureMessage = '';

        if ($this->readyToInvest === "Non, j'étais juste curieux.") {
            $this->submissionFailed = true;
            $this->failureMessage = 'Merci pour votre intérêt. Il semble que nos services ne correspondent pas à vos besoins actuels.';
            return;
        }

        $formData = $this->only([
            'firstName', 'lastName', 'email', 'phone', 'website',
            'businessYears', 'mainObjective', 'onlineAdvertisingExperience',
            'monthlyBudget', 'readyToInvest', 'consent'
        ]);
        
        // Normalize form data keys to snake_case for the service
        $normalizedData = [
            'first_name' => $formData['firstName'],
            'last_name' => $formData['lastName'],
            'email' => $formData['email'],
            'phone' => $formData['phone'],
            'website' => $formData['website'],
            'business_years' => $formData['businessYears'],
            'main_objective' => $formData['mainObjective'],
            'online_advertising_experience' => $formData['onlineAdvertisingExperience'],
            'monthly_budget' => $formData['monthlyBudget'],
            'ready_to_invest' => $formData['readyToInvest'],
            'consent' => $formData['consent'],
        ];

        try {
            $experiment = $this->experimentId ? Experiment::find($this->experimentId) : null;
            $variation = $this->variationId ? Variation::find($this->variationId) : null;

            $submissionService->create($normalizedData, request(), $experiment, $variation);

            $this->submissionSuccess = true;
            $this->resetForm();
            
        } catch (\Exception $e) {
            Log::error('Form submission failed', [
                'error' => $e->getMessage(),
                'data' => $normalizedData,
            ]);

            $this->submissionFailed = true;
            $this->failureMessage = 'Une erreur s\'est produite lors de la soumission de votre demande. Veuillez réessayer plus tard.';
        }
    }

    public function render()
    {
        return view('livewire.landing-page-form');
    }
}
