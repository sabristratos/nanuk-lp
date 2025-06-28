<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;
use App\Services\TestimonialService;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $languageFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'languageFilter' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->authorize('view-testimonials');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedLanguageFilter(): void
    {
        $this->resetPage();
    }

    public function toggleActive(Testimonial $testimonial): void
    {
        $this->authorize('edit-testimonials');
        
        try {
            app(TestimonialService::class)->toggleActive($testimonial);
            
            Flux::toast(
                text: __('Testimonial status updated successfully.'),
                heading: __('Success'),
                variant: 'success'
            );
            
            $this->dispatch('testimonial-updated');
        } catch (\Exception $e) {
            Log::error('Failed to toggle testimonial status: ' . $e->getMessage());
            Flux::toast(
                text: $e->getMessage() ?: __('Failed to update testimonial status. Please try again.'),
                heading: __('Error'),
                variant: 'danger'
            );
        }
    }

    public function deleteTestimonial(Testimonial $testimonial): void
    {
        $this->authorize('delete-testimonials');
        
        try {
            app(TestimonialService::class)->deleteTestimonial($testimonial);
            
            Flux::toast(
                text: __('Testimonial deleted successfully.'),
                heading: __('Success'),
                variant: 'success'
            );
            
            $this->dispatch('testimonial-deleted');
        } catch (\Exception $e) {
            Log::error('Failed to delete testimonial: ' . $e->getMessage());
            Flux::toast(
                text: $e->getMessage() ?: __('Failed to delete testimonial. Please try again.'),
                heading: __('Error'),
                variant: 'danger'
            );
        }
    }

    public function render()
    {
        $testimonials = Testimonial::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('quote', 'like', '%' . $this->search . '%')
                      ->orWhere('author_name', 'like', '%' . $this->search . '%')
                      ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->languageFilter, function ($query) {
                $query->where('language', $this->languageFilter);
            })
            ->ordered()
            ->paginate(10);

        return view('livewire.admin.testimonials.index', [
            'testimonials' => $testimonials,
        ])->layout('components.layouts.admin', [
            'title' => __('Testimonials'),
        ]);
    }
} 