<?php

namespace App\Livewire\Admin\Experiments;

use App\Enums\ContentType;
use App\Enums\ContentElementType;
use App\Enums\ConfigurationElementType;
use App\Enums\ExperimentStatus;
use App\Enums\ModificationType;
use App\Models\Experiment;
use App\Models\VariationModification;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule as LivewireRule;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Services\LocaleService;

#[Layout('components.layouts.admin')]
class ManageExperiment extends Component
{
    use AuthorizesRequests;

    public ?Experiment $experiment = null;

    #[LivewireRule('required|string|max:255')]
    public string $name = '';

    #[LivewireRule('nullable|string')]
    public string $description = '';

    #[LivewireRule('required')]
    public string $status = 'draft';

    #[LivewireRule('nullable|date')]
    public ?string $start_date = null;

    #[LivewireRule('nullable|date|after_or_equal:start_date')]
    public ?string $end_date = null;

    public array $variations = [];

    public array $locales;

    #[Url(as: 'tab', keep: true)]
    public ?string $currentLocale = null;

    public function mount(?Experiment $experiment, LocaleService $localeService): void
    {
        $this->experiment = $experiment;
        $this->locales = $localeService->getAvailableLocalesForSelect();

        if (is_null($this->currentLocale) || ! array_key_exists($this->currentLocale, $this->locales)) {
            $this->currentLocale = array_key_first($this->locales);
        }

        if ($this->experiment?->exists) {
            Gate::authorize('edit-experiments');
            $this->name = $this->experiment->name;
            $this->description = $this->experiment->description ?? '';
            $this->status = $this->experiment->status->value;
            $this->start_date = $this->experiment->start_date?->format('Y-m-d');
            $this->end_date = $this->experiment->end_date?->format('Y-m-d');
            $this->variations = $this->experiment->variations()->with('modifications')->get()->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'name' => $variation->name,
                    'description' => $variation->description,
                    'weight' => $variation->weight,
                    'modifications' => $variation->modifications->map(function ($modification) {
                        return [
                            'id' => $modification->id,
                            'type' => $modification->type->value,
                            'target' => $modification->target,
                            'payload' => $modification->payload,
                        ];
                    })->toArray(),
                ];
            })->toArray();
        } else {
            Gate::authorize('create-experiments');
            $this->addVariation();
        }
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(array_column(ExperimentStatus::cases(), 'value'))],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'variations' => 'array|min:1',
            'variations.*.name' => 'required|string|max:255',
            'variations.*.description' => 'nullable|string',
            'variations.*.weight' => 'required|integer|min:0',
            'variations.*.modifications' => 'array',
            'variations.*.modifications.*.type' => ['required', Rule::in(ModificationType::values())],
            'variations.*.modifications.*.target' => 'required|string|max:255',
            'variations.*.modifications.*.payload' => 'required|array',
        ];

        // Specific payload validation can be added here based on type if needed
        // For example:
        // $rules['variations.*.modifications.*.payload.color'] = 'required_if:variations.*.modifications.*.type,style|string';

        return $rules;
    }

    public function addVariation(): void
    {
        $this->variations[] = [
            'id' => null,
            'name' => '',
            'description' => '',
            'weight' => 50,
            'modifications' => [],
        ];
    }

    public function removeVariation(int $index): void
    {
        unset($this->variations[$index]);
        $this->variations = array_values($this->variations);
    }

    public function addModification(int $variationIndex): void
    {
        $this->variations[$variationIndex]['modifications'][] = [
            'id' => null,
            'type' => ModificationType::Text->value,
            'target' => '',
            'payload' => $this->getDefaultPayload(ModificationType::Text->value),
        ];
    }
    
    public function removeModification(int $variationIndex, int $modificationIndex): void
    {
        unset($this->variations[$variationIndex]['modifications'][$modificationIndex]);
        $this->variations[$variationIndex]['modifications'] = array_values($this->variations[$variationIndex]['modifications']);
    }

    public function updatedVariationsModifications($value, $key): void
    {
        $parts = explode('.', $key);
        // 0: variations, 1: variationIndex, 2: modifications, 3: modIndex, 4: field
        if (count($parts) < 5 || $parts[4] !== 'type') {
            return;
        }

        $variationIndex = (int) $parts[1];
        $modIndex = (int) $parts[3];

        // When type changes, reset the payload to the default for that type
        $this->variations[$variationIndex]['modifications'][$modIndex]['payload'] = $this->getDefaultPayload($value);
    }
    
    private function getDefaultPayload(string $type): array
    {
        return match ($type) {
            ModificationType::Text->value => ['multilang_content' => array_fill_keys(array_keys($this->locales), '')],
            ModificationType::Style->value => ['property' => 'color', 'value' => '#000000'],
            ModificationType::Visibility->value => ['visible' => true],
            ModificationType::Classes->value => ['classes' => ''],
            ModificationType::Layout->value => ['css_classes' => ''],
            default => [],
        };
    }

    public function save(): void
    {
        $isCreating = !$this->experiment?->exists;

        if ($isCreating) {
            Gate::authorize('create-experiments');
        } else {
            Gate::authorize('edit-experiments');
        }

        try {
            $validatedData = $this->validate();

            $totalWeight = collect($this->variations)->sum('weight');
            if ($totalWeight !== 100) {
                throw ValidationException::withMessages([
                    'variations' => 'The sum of variation weights must be 100.',
                ]);
            }

            $experimentData = [
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ];

            DB::transaction(function () use ($isCreating, $experimentData) {
                if ($isCreating) {
                    $this->experiment = Experiment::create($experimentData);
                } else {
                    $this->experiment->update($experimentData);
                }

                $variationIds = [];
                foreach ($this->variations as $variationData) {
                    $variation = $this->experiment->variations()->updateOrCreate(
                        ['id' => $variationData['id'] ?? null],
                        [
                            'name' => $variationData['name'],
                            'description' => $variationData['description'],
                            'weight' => $variationData['weight'],
                        ]
                    );
                    $variationIds[] = $variation->id;

                    $modificationIds = [];
                    if (isset($variationData['modifications'])) {
                        foreach ($variationData['modifications'] as $modData) {
                            $modification = $variation->modifications()->updateOrCreate(
                                ['id' => $modData['id'] ?? null],
                                [
                                    'type' => $modData['type'],
                                    'target' => $modData['target'],
                                    'payload' => $modData['payload'],
                                ]
                            );
                            $modificationIds[] = $modification->id;
                        }
                    }
                    $variation->modifications()->whereNotIn('id', $modificationIds)->delete();
                }
                $this->experiment->variations()->whereNotIn('id', $variationIds)->delete();

                Cache::forget("content.collection.{$this->experiment->id}");
            });

            Flux::toast(
                text: $isCreating ? __('Experiment created successfully.') : __('Experiment updated successfully.'),
                heading: __('Success'),
                variant: 'success'
            );

            if ($isCreating) {
                $this->redirect(route('admin.experiments.edit', $this->experiment));
            }
        } catch (ValidationException $e) {
            $this->dispatch('validation-errors', errors: $e->errors());
            throw $e;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.experiments.manage-experiment', [
            'statuses' => ExperimentStatus::cases(),
            'modificationTypes' => ModificationType::cases(),
        ]);
    }
} 