<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;
use App\Services\TestimonialService;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ManageTestimonial extends Component
{
    use AuthorizesRequests;

    public ?Testimonial $testimonial = null;
    
    public ?string $quote = null;
    public ?string $author_name = null;
    public ?string $company_name = null;
    public ?string $position = null;
    public ?int $rating = 5;
    public ?bool $is_active = true;
    public ?int $order = 0;
    public ?string $language = 'fr';

    protected $rules = [
        'quote' => 'required|string|max:1000',
        'author_name' => 'nullable|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'rating' => 'required|integer|min:1|max:5',
        'is_active' => 'nullable|boolean',
        'order' => 'nullable|integer|min:0',
        'language' => 'nullable|string|in:fr,en',
    ];

    public function mount(?Testimonial $testimonial): void
    {
        $this->testimonial = $testimonial;
        
        if ($this->testimonial?->exists) {
            $this->authorize('edit-testimonials');
            $this->loadTestimonialData();
        } else {
            $this->authorize('create-testimonials');
        }
    }

    private function loadTestimonialData(): void
    {
        $this->quote = $this->testimonial->quote;
        $this->author_name = $this->testimonial->author_name;
        $this->company_name = $this->testimonial->company_name;
        $this->position = $this->testimonial->position;
        $this->rating = $this->testimonial->rating ?? 5;
        $this->is_active = $this->testimonial->is_active ?? true;
        $this->order = $this->testimonial->order ?? 0;
        $this->language = $this->testimonial->language ?? 'fr';
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'quote' => $this->quote,
            'author_name' => $this->author_name,
            'company_name' => $this->company_name,
            'position' => $this->position,
            'rating' => $this->rating,
            'is_active' => $this->is_active,
            'order' => $this->order,
            'language' => $this->language,
        ];

        $service = app(TestimonialService::class);

        try {
            if ($this->testimonial?->exists) {
                $service->updateTestimonial($this->testimonial, $data);
                Flux::toast(
                    text: __('Testimonial updated successfully.'),
                    heading: __('Success'),
                    variant: 'success'
                );
                $this->dispatch('testimonial-updated');
            } else {
                $testimonial = $service->createTestimonial($data);
                Flux::toast(
                    text: __('Testimonial created successfully.'),
                    heading: __('Success'),
                    variant: 'success'
                );
                $this->dispatch('testimonial-created');
                $this->redirect(route('admin.testimonials.edit', $testimonial), navigate: true);
                return;
            }

            $this->redirect(route('admin.testimonials.index'), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors from the service
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            Flux::toast(
                text: __('Please correct the errors below.'),
                heading: __('Validation Error'),
                variant: 'danger'
            );
        } catch (\Exception $e) {
            Log::error('Failed to save testimonial: ' . $e->getMessage());
            Flux::toast(
                text: $e->getMessage() ?: __('Failed to save testimonial. Please try again.'),
                heading: __('Error'),
                variant: 'danger'
            );
        }
    }

    public function render()
    {
        return view('livewire.admin.testimonials.manage-testimonial')->layout('components.layouts.admin', [
            'title' => $this->testimonial ? __('Edit Testimonial') : __('Create Testimonial'),
        ]);
    }
} 