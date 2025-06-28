<div>
    <div class="flex justify-between items-center mb-6">
        <flux:heading size="xl">
            {{ $testimonial ? __('Edit Testimonial') : __('Create Testimonial') }}
        </flux:heading>
        <flux:button href="{{ route('admin.testimonials.index') }}" variant="ghost">
            <flux:icon name="arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Back to Testimonials') }}
        </flux:button>
    </div>

    <div class="max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <!-- Quote -->
            <flux:textarea
                wire:model="quote"
                label="{{ __('Testimonial Quote') }}"
                placeholder="{{ __('Enter the testimonial quote...') }}"
                rows="4"
                required
            />
            <flux:error name="quote" />

            <!-- Author Information -->
            <div class="space-y-4">
                <flux:input
                    wire:model="author_name"
                    label="{{ __('Author Name') }}"
                    placeholder="{{ __('Author name (optional)') }}"
                />
                <flux:error name="author_name" />

                <flux:input
                    wire:model="company_name"
                    label="{{ __('Company Name') }}"
                    placeholder="{{ __('Company name (optional)') }}"
                />
                <flux:error name="company_name" />

                <flux:input
                    wire:model="position"
                    label="{{ __('Position') }}"
                    placeholder="{{ __('Job position (optional)') }}"
                />
                <flux:error name="position" />
            </div>

            <!-- Rating and Language -->
            <div class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Rating') }}</flux:label>
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <flux:button
                                type="button"
                                wire:click="$set('rating', {{ $i }})"
                                variant="ghost"
                                class="p-1 {{ $i <= $rating ? 'text-yellow-400' : 'text-zinc-300 hover:text-zinc-400' }}"
                            >
                                @if($i <= $rating)
                                    <flux:icon name="star" variant="solid" class="w-6 h-6" />
                                @else
                                    <flux:icon name="star" variant="outline" class="w-6 h-6" />
                                @endif
                            </flux:button>
                        @endfor
                        <flux:text class="ml-2 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $rating }}/5
                        </flux:text>
                    </div>
                    <flux:error name="rating" />
                </flux:field>

                <flux:select
                    wire:model="language"
                    label="{{ __('Language') }}"
                >
                    <flux:select.option value="fr">{{ __('French') }}</flux:select.option>
                    <flux:select.option value="en">{{ __('English') }}</flux:select.option>
                </flux:select>
                <flux:error name="language" />
            </div>

            <!-- Status and Order -->
            <div class="space-y-4">
                <flux:switch
                    wire:model="is_active"
                    label="{{ __('Active') }}"
                    description="{{ __('Show this testimonial on the website') }}"
                    align="left"
                />
                <flux:error name="is_active" />

                <flux:input
                    wire:model="order"
                    type="number"
                    label="{{ __('Display Order') }}"
                    description="{{ __('Lower numbers appear first') }}"
                    min="0"
                />
                <flux:error name="order" />
            </div>

            <!-- Preview -->
            @if($quote)
                <flux:separator />
                <div>
                    <flux:heading size="lg" class="mb-4">
                        {{ __('Preview') }}
                    </flux:heading>
                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-4">
                        <flux:text class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed mb-3">
                            "{{ $quote }}"
                        </flux:text>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <flux:icon name="star" variant="solid" class="w-4 h-4 text-yellow-400" />
                                @else
                                    <flux:icon name="star" variant="outline" class="w-4 h-4 text-zinc-300" />
                                @endif
                            @endfor
                        </div>
                        @if($author_name || $company_name)
                            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400 mt-2">
                                {{ $author_name }}{{ $author_name && $company_name ? ' - ' : '' }}{{ $company_name }}
                            </flux:text>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <flux:separator />
            <div class="flex justify-end gap-3">
                <flux:button
                    href="{{ route('admin.testimonials.index') }}"
                    variant="ghost"
                >
                    {{ __('Cancel') }}
                </flux:button>
                
                <flux:button
                    type="submit"
                    variant="primary"
                >
                    {{ $testimonial ? __('Update Testimonial') : __('Create Testimonial') }}
                </flux:button>
            </div>
        </form>
    </div>
</div> 