<div
    x-data="experimentForm()"
    @validation-errors.window="handleValidationErrors($event.detail.errors)"
>
    <div class="flex justify-between items-center mb-4">
        <flux:heading size="xl">
            {{ $experiment?->exists ? __('Edit Experiment') : __('Create Experiment') }}
        </flux:heading>
        <flux:button :href="route('admin.experiments.index')" variant="outline" icon="arrow-left" tooltip="{{ __('Back to Experiments') }}">
            {{ __('Back to Experiments') }}
        </flux:button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-12 mt-8">
        <div class="lg:col-span-2">
            <form wire:submit.prevent="save">
                <div class="space-y-6">
                    <flux:input 
                        wire:model="name" 
                        label="{{ __('Name') }}" 
                        placeholder="e.g., Homepage Hero Test" 
                        description:trailing="{{ __('A descriptive name for internal reference.') }}" 
                        required 
                    />
                    <flux:textarea 
                        wire:model="description" 
                        label="{{ __('Description') }}" 
                        placeholder="e.g., Testing a new headline and CTA for the homepage hero section." 
                        description:trailing="{{ __('A brief summary of what this experiment is testing.') }}" 
                    />
                    <flux:select 
                        wire:model="status" 
                        label="{{ __('Status') }}" 
                        description:trailing="{{ __('Drafts are not visible to users. Active experiments will run if within their date range.') }}" 
                        required
                    >
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}">{{ str($status->name)->title() }}</option>
                        @endforeach
                    </flux:select>
            
                    <div class="grid grid-cols-2 gap-4">
                        <flux:input 
                            wire:model="start_date" 
                            type="date" 
                            label="{{ __('Start Date') }}" 
                            description:trailing="{{ __('Optional. The date the experiment will begin.') }}" 
                        />
                        <flux:input 
                            wire:model="end_date" 
                            type="date" 
                            label="{{ __('End Date') }}" 
                            description:trailing="{{ __('Optional. The date the experiment will end.') }}" 
                        />
                    </div>
                </div>
            
                <flux:separator variant="subtle" class="my-8" />
        
                <div>
                    <flux:heading size="lg" id="variations-heading">{{ __('Variations') }}</flux:heading>
                    <flux:text variant="subtle" class="mt-1">
                        {{ __('Define the different variations for this experiment. The sum of all variation weights must be 100.') }}
                    </flux:text>
        
                    <div class="space-y-6 mt-6">
                        @foreach ($variations as $index => $variation)
                            <div
                                class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg transition-colors duration-500"
                                wire:key="variation-{{ $index }}"
                                x-ref="variation-{{ $index }}"
                            >
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <flux:input 
                                        wire:model="variations.{{ $index }}.name" 
                                        label="{{ __('Variation Name') }}" 
                                        placeholder="e.g., Control or New Headline" 
                                        required 
                                    />
                                    <flux:input 
                                        wire:model="variations.{{ $index }}.weight" 
                                        type="number" 
                                        label="{{ __('Weight (%)') }}" 
                                        tooltip="{{ __('The percentage of users who will see this variation. All weights must sum to 100.') }}" 
                                        required 
                                    />
                                     <div class="flex items-end space-x-2">
                                        <flux:button 
                                            type="button" 
                                            variant="outline" 
                                            :href="$experiment?->exists ? route('home', ['experiment_id' => $experiment->id, 'variation_id' => $variation['id'] ?? 'preview_' . $index]) : '#'" 
                                            target="_blank"
                                            :disabled="!$experiment?->exists"
                                            tooltip="{{ __('Preview this variation in a new tab. Only works after the experiment is created.') }}"
                                        >
                                            {{ __('Preview') }}
                                        </flux:button>
                                        @if (count($variations) > 1)
                                            <flux:button 
                                                type="button" 
                                                variant="danger" 
                                                wire:click="removeVariation({{ $index }})" 
                                                tooltip="{{ __('Remove this variation.') }}"
                                            >
                                                {{ __('Remove') }}
                                            </flux:button>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <flux:textarea 
                                        wire:model="variations.{{ $index }}.description" 
                                        label="{{ __('Description') }}" 
                                        placeholder="e.g., The original version of the hero section."
                                        rows="2"
                                    />
                                </div>
        
                                <flux:separator variant="subtle" class="my-6" />
        
                                <div x-data="{}" class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                                    <div class="flex justify-between items-center">
                                        <flux:heading size="sm">{{ __('Changes') }}</flux:heading>
                                        <flux:button type="button" size="sm" variant="outline" wire:click="addModification({{ $index }})">{{ __('Add Change') }}</flux:button>
                                    </div>
                                    <flux:text variant="subtle" class="mt-1 text-sm">{{ __('Define the modifications for this variation.') }}</flux:text>
                                
                                    <div class="space-y-4 mt-4">
                                        @foreach($variation['modifications'] as $modIndex => $modification)
                                            <div
                                                wire:key="variation-{{ $index }}-modification-{{ $modIndex }}"
                                                class="p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 transition-colors duration-500"
                                                x-ref="variation-{{ $index }}-modification-{{ $modIndex }}"
                                            >
                                                <div class="flex justify-between items-center">
                                                    <h4 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">
                                                        {{ __('Change #:number', ['number' => $modIndex + 1]) }}
                                                    </h4>
                                                    <flux:button
                                                        variant="danger"
                                                        icon="trash"
                                                        wire:click="removeModification({{ $index }}, {{ $modIndex }})"
                                                        tooltip="{{ __('Remove this change') }}"
                                                    />
                                                </div>
                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                    {{-- Type of Change --}}
                                                    <flux:select
                                                        wire:model.live="variations.{{ $index }}.modifications.{{ $modIndex }}.type"
                                                        label="{{ __('Type of Change') }}"
                                                        description:trailing="Select the type of modification to apply."
                                                        required
                                                    >
                                                        @foreach($modificationTypes as $type)
                                                            <option value="{{ $type->value }}">{{ $type->getLabel() }}</option>
                                                        @endforeach
                                                    </flux:select>
                                
                                                    {{-- Target Element --}}
                                                    @if($modification['type'] !== \App\Enums\ModificationType::Component->value)
                                                        <flux:input
                                                            wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.target"
                                                            label="{{ __('Target Element') }}"
                                                            description:trailing="Enter the data-key attribute from your Blade file (e.g., hero.title)."
                                                            required
                                                        />
                                                    @else
                                                        <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <div>
                                                                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ __('Target: Form Component') }}</p>
                                                                <p class="text-xs text-green-600 dark:text-green-400">{{ __('Automatically configured for form display and positioning') }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                
                                                <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                                    {{-- Conditional Fields --}}
                                                    @switch($modification['type'])
                                                        @case(\App\Enums\ModificationType::Text->value)
                                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Content') }}</label>
                                                            <flux:tab.group class="mt-1">
                                                                <flux:tabs>
                                                                    @foreach($this->locales as $localeCode => $localeName)
                                                                        <flux:tab name="{{ $localeCode }}">{{ $localeName }}</flux:tab>
                                                                    @endforeach
                                                                </flux:tabs>
                                                                @foreach($this->locales as $localeCode => $localeName)
                                                                    <flux:tab.panel name="{{ $localeCode }}" class="p-4 bg-white dark:bg-zinc-800 rounded-b-lg">
                                                                        <flux:textarea
                                                                            wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.multilang_content.{{ $localeCode }}"
                                                                            rows="4"
                                                                            placeholder="Enter content for {{ $localeName }}..."
                                                                        />
                                                                    </flux:tab.panel>
                                                                @endforeach
                                                            </flux:tab.group>
                                                            @break
                                
                                                        @case(\App\Enums\ModificationType::Style->value)
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <flux:select wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.property" label="{{ __('CSS Property') }}">
                                                                    <option value="color">{{ __('Text Color') }}</option>
                                                                    <option value="backgroundColor">{{ __('Background Color') }}</option>
                                                                </flux:select>
                                                                <flux:input wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.value" type="color" label="{{ __('Value') }}" />
                                                            </div>
                                                            @break
                                
                                                        @case(\App\Enums\ModificationType::Visibility->value)
                                                            <flux:select wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.visible" label="{{ __('Visibility') }}">
                                                                <option value="1">{{ __('Visible') }}</option>
                                                                <option value="0">{{ __('Hidden') }}</option>
                                                            </flux:select>
                                                            @break
                                
                                                        @case(\App\Enums\ModificationType::Classes->value)
                                                            <flux:textarea wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.classes" label="{{ __('Custom CSS Classes') }}" description:trailing="Enter one or more Tailwind CSS classes." />
                                                            @break
                                                            
                                                        @case(\App\Enums\ModificationType::Layout->value)
                                                            <flux:textarea wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.css_classes" label="{{ __('Component Root Classes') }}" description:trailing="Replaces all classes on the component's root element." />
                                                            @break
                                                            
                                                        @case(\App\Enums\ModificationType::Component->value)
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <flux:select wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.show" label="{{ __('Display Mode') }}">
                                                                    <option value="modal">{{ __('Modal') }}</option>
                                                                    <option value="embedded">{{ __('Embedded') }}</option>
                                                                </flux:select>
                                                                <flux:select wire:model="variations.{{ $index }}.modifications.{{ $modIndex }}.payload.position" label="{{ __('Position') }}">
                                                                    <option value="hero">{{ __('After Hero') }}</option>
                                                                    <option value="end">{{ __('End of Page') }}</option>
                                                                </flux:select>
                                                            </div>
                                                            @break
                                
                                                    @endswitch
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                         @error('variations') <div class="text-red-500 text-sm mt-2">{{ $message }}</div> @enderror
                    </div>
        
                    <flux:button type="button" variant="outline" wire:click="addVariation" class="mt-6">{{ __('Add Variation') }}</flux:button>
                </div>
            
                <div class="flex justify-end space-x-3 mt-8">
                    <flux:button type="button" variant="outline" :href="route('admin.experiments.index')">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            {{ $experiment?->exists ? __('Save Changes') : __('Create Experiment') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $experiment?->exists ? __('Saving...') : __('Creating...') }}
                        </span>
                    </flux:button>
                </div>
            </form>
        </div>

        <div class="hidden lg:block p-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg space-y-4 h-fit sticky top-24">
            <flux:heading level="3" size="lg">{{ __('How Our A/B Testing Works') }}</flux:heading>

            <flux:text>
                {{ __("Our A/B testing system allows you to experiment with changes on your website without writing any code. You can modify text, styles, visibility, and more by targeting specific elements on a page.") }}
            </flux:text>

            <flux:heading level="4">{{ __('1. Finding the Target Element') }}</flux:heading>

            <flux:text>
                {{ __("Every element you can test has a unique identifier called a") }} <code class="text-sm font-mono p-1 bg-zinc-200 dark:bg-zinc-700 rounded">data-key</code>. {{ __("To find it:") }}
            </flux:text>

            <ol class="list-decimal list-inside space-y-2 text-zinc-600 dark:text-zinc-400">
                <li>{{ __('Go to the page on your site where you want to make a change.') }}</li>
                <li>{{ __('Right-click the element you want to modify (e.g., a button, a heading) and select "Inspect" or "Inspect Element".') }}</li>
                <li>{{ __('The browser\'s developer tools will open, highlighting the element\'s HTML.') }}</li>
                <li>{{ __('Look for an attribute like') }} <code class="text-sm font-mono p-1 bg-zinc-200 dark:bg-zinc-700 rounded">data-key="some-name"</code>. {{ __('The value in the quotes is your Target Element.') }}</li>
            </ol>

            <flux:text>
                <em class="text-zinc-500 dark:text-zinc-400 not-italic">{{ __('Example:') }}</em> {{ __('You might find') }} <code class="text-sm font-mono p-1 bg-zinc-200 dark:bg-zinc-700 rounded">&lt;h1 data-key="hero.title"&gt;...&lt;/h1&gt;</code>. {{ __('Your target would be') }} <code class="text-sm font-mono p-1 bg-zinc-200 dark:bg-zinc-700 rounded">hero.title</code>.
            </flux:text>

            <flux:heading level="4">{{ __('2. Example: Changing a Button\'s Text') }}</flux:heading>

            <flux:text>
                {{ __("Let's test if changing 'Sign Up' to 'Get Started' on a button increases clicks. Assume the button's data-key is") }} <code class="text-sm font-mono p-1 bg-zinc-200 dark:bg-zinc-700 rounded">cta.main_button</code>.
            </flux:text>

            <div class="space-y-3 text-zinc-600 dark:text-zinc-400">
                <ol class="list-decimal list-inside space-y-4">
                    <li>
                        <flux:text variant="strong" inline>{{ __('Setup Experiment:') }}</flux:text>
                        <flux:text class="pl-5">{{ __("Fill out the Name, Description, and set the Status to 'Draft'.") }}</flux:text>
                    </li>
                    <li>
                        <flux:text variant="strong" inline>{{ __('Create Variations:') }}</flux:text>
                        <ul class="list-disc list-inside mt-1 space-y-1 pl-5">
                            <li><flux:text variant="strong" inline>{{ __('Variation 1 (Control):') }}</flux:text> {{ __("Name it 'Control' or 'Original Button'. This is your baseline and needs no changes.") }}</li>
                            <li><flux:text variant="strong" inline>{{ __('Variation 2 (Test):') }}</flux:text> {{ __("Name it 'New Button Text'.") }}</li>
                            <li>{{ __("Set the Weight for each to 50% to split traffic evenly.") }}</li>
                        </ul>
                    </li>
                    <li>
                        <flux:text variant="strong" inline>{{ __('Add a Change:') }}</flux:text>
                        <flux:text class="pl-5">{{ __("In your 'New Button Text' variation, click 'Add Change'.") }}</flux:text>
                        <ul class="list-disc list-inside mt-1 space-y-1 pl-5">
                            <li><flux:text variant="strong" inline>{{ __('Type of Change:') }}</flux:text> {{ __("Select 'Text'.") }}</li>
                            <li><flux:text variant="strong" inline>{{ __('Target Element:') }}</flux:text> {{ __("Enter") }} <code class="text-sm font-mono p-1 bg-zinc-200 dark:bg-zinc-700 rounded">cta.main_button</code>.</li>
                            <li><flux:text variant="strong" inline>{{ __('Content:') }}</flux:text> {{ __("In the text box that appears, type 'Get Started'.") }}</li>
                        </ul>
                    </li>
                    <li>
                        <flux:text variant="strong" inline>{{ __('Save and Activate:') }}</flux:text>
                        <flux:text class="pl-5">{{ __("Save the experiment. When you're ready to start, change the Status to 'Active' and save again.") }}</flux:text>
                    </li>
                </ol>
            </div>

            <flux:text>
                {{ __("That's it! The system will now show 50% of visitors the original button and 50% the new 'Get Started' button, tracking which one performs better.") }}
            </flux:text>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function experimentForm() {
        return {
            handleValidationErrors(errors) {
                const firstErrorKey = Object.keys(errors)[0];
                if (!firstErrorKey) return;

                let targetElement = null;

                if (firstErrorKey === 'variations') {
                    targetElement = document.querySelector('#variations-heading');
                } else if (firstErrorKey.startsWith('variations.')) {
                    const parts = firstErrorKey.split('.');
                    const variationIndex = parts[1];

                    if (parts.length > 3 && parts[2] === 'modifications') {
                        const modificationIndex = parts[3];
                        targetElement = this.$refs[`variation-${variationIndex}-modification-${modificationIndex}`];
                    } else {
                        targetElement = this.$refs[`variation-${variationIndex}`];
                    }
                }

                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    targetElement.classList.add('bg-danger-500/10', 'dark:bg-danger-500/20');
                    setTimeout(() => {
                        targetElement.classList.remove('bg-danger-500/10', 'dark:bg-danger-500/20');
                    }, 3000);
                }
            }
        }
    }
</script>
@endpush 