<?php

namespace App\Livewire;

use App\Models\Experiment;
use App\Models\Variation;
use App\Services\MetricsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Validate;

class LandingPageForm extends Component
{
    public bool $showModal = false;
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

    #[Validate('required|string|max:20')] // Adjust max length as needed for phone format
    public string $phone = '';

    #[Validate('nullable|url|max:255')]
    public string $website = '';

    #[Validate('required|string')]
    public string $primaryGoal = '';

    #[Validate('required|string')]
    public string $digitalMarketingExperience = '';

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

        if ($this->displayMode === 'modal') {
            $this->showModal = false;
        } else {
            $this->showModal = true;
        }
    }

    public function openModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->submissionFailed = false;
        $this->submissionSuccess = false;
        $this->showModal = true;
        $this->consent = false; // Also reset consent if needed, or default to true
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function resetForm(): void
    {
        $this->firstName = '';
        $this->lastName = '';
        $this->email = '';
        $this->phone = '';
        $this->website = '';
        $this->primaryGoal = '';
        $this->digitalMarketingExperience = '';
        $this->readyToInvest = '';
        $this->consent = false; // Also reset consent if needed, or default to true
    }

    public function submit(MetricsService $metricsService)
    {
        $this->validate();

        $this->submissionFailed = false;
        $this->submissionSuccess = false;
        $this->failureMessage = '';

        // Record conversion for A/B testing
        if ($this->experimentId && $this->variationId) {
            try {
                $experiment = Experiment::find($this->experimentId);
                $variation = Variation::find($this->variationId);

                if ($experiment && $variation) {
                    $payload = $this->only([
                        'firstName', 'lastName', 'email', 'phone', 'website',
                        'primaryGoal', 'digitalMarketingExperience', 'readyToInvest', 'consent'
                    ]);
                    $metricsService->recordConversion($experiment, $variation, 'form_submission', $payload);
                }
            } catch (\Exception $e) {
                Log::error('Failed to record experiment conversion', [
                    'error' => $e->getMessage(),
                    'experiment_id' => $this->experimentId,
                    'variation_id' => $this->variationId,
                ]);
            }
        }

        if ($this->readyToInvest === 'Non je ne suis pas intéressé pour l\'instant') {
            $this->submissionFailed = true;
            $this->failureMessage = 'Merci pour votre intérêt. Il semble que nos services ne correspondent pas à vos besoins actuels.';
            // $this->resetForm(); // Optionally reset form
            return;
        }

        try {
            $response = Http::post('https://n8n.nanukweb.ca/webhook/go-nanukweb-1', [
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'phone' => $this->phone,
                'website' => $this->website,
                'primary_goal' => $this->primaryGoal,
                'digital_marketing_experience' => $this->digitalMarketingExperience,
                'ready_to_invest' => $this->readyToInvest,
                'consent_given' => $this->consent,
                'submitted_at' => now()->toIso8601String(),
            ]);

            if ($response->successful()) {
                $this->submissionSuccess = true;
                // $this->resetForm(); // Reset form on success
                // $this->showModal = false; // Close modal on success
                // Potentially emit an event to redirect or show a global success message
                // session()->flash('form-success', 'Votre demande a été soumise avec succès!');
            } else {
                Log::error('Webhook submission failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $this->submissionFailed = true;
                $this->failureMessage = 'Une erreur s\'est produite lors de la soumission de votre demande. Veuillez réessayer plus tard.';
            }
        } catch (\Exception $e) {
            Log::error('Webhook submission exception', ['message' => $e->getMessage()]);
            $this->submissionFailed = true;
            $this->failureMessage = 'Une erreur de communication s\'est produite. Veuillez réessayer plus tard.';
        }
    }

    public function render()
    {
        return view('livewire.landing-page-form');
    }
}
