<div>
    <div class="flex justify-between items-center mb-6">
        <flux:heading size="xl">{{ __('Testimonials') }}</flux:heading>
        @can('create-testimonials')
            <flux:button href="{{ route('admin.testimonials.create') }}" variant="primary">
                <flux:icon name="plus" class="w-4 h-4 mr-2" />
                {{ __('Add Testimonial') }}
            </flux:button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input
                wire:model.live="search"
                placeholder="{{ __('Search testimonials...') }}"
                icon="magnifying-glass"
            />
            
            <flux:select wire:model.live="statusFilter">
                <flux:select.option value="">{{ __('All Status') }}</flux:select.option>
                <flux:select.option value="active">{{ __('Active') }}</flux:select.option>
                <flux:select.option value="inactive">{{ __('Inactive') }}</flux:select.option>
            </flux:select>
            
            <flux:select wire:model.live="languageFilter">
                <flux:select.option value="">{{ __('All Languages') }}</flux:select.option>
                <flux:select.option value="fr">{{ __('French') }}</flux:select.option>
                <flux:select.option value="en">{{ __('English') }}</flux:select.option>
            </flux:select>
        </div>
    </div>

    <!-- Testimonials Table -->
    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Quote') }}</flux:table.column>
            <flux:table.column>{{ __('Author') }}</flux:table.column>
            <flux:table.column>{{ __('Rating') }}</flux:table.column>
            <flux:table.column>{{ __('Language') }}</flux:table.column>
            <flux:table.column>{{ __('Status') }}</flux:table.column>
            <flux:table.column>{{ __('Order') }}</flux:table.column>
            <flux:table.column>{{ __('Actions') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($testimonials as $testimonial)
                <flux:table.row wire:key="testimonial-{{ $testimonial->id }}">
                    <flux:table.cell>
                        <div class="max-w-xs">
                            <flux:text class="line-clamp-2">
                                {{ Str::limit($testimonial->quote, 100) }}
                            </flux:text>
                        </div>
                    </flux:table.cell>
                    
                    <flux:table.cell>
                        <div>
                            @if($testimonial->author_name)
                                <flux:heading size="sm">{{ $testimonial->author_name }}</flux:heading>
                            @endif
                            @if($testimonial->company_name)
                                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ $testimonial->company_name }}</flux:text>
                            @endif
                        </div>
                    </flux:table.cell>
                    
                    <flux:table.cell>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <flux:icon name="star" variant="solid" class="w-4 h-4 text-yellow-400" />
                                @else
                                    <flux:icon name="star" variant="outline" class="w-4 h-4 text-zinc-300" />
                                @endif
                            @endfor
                        </div>
                    </flux:table.cell>
                    
                    <flux:table.cell>
                        <flux:badge 
                            color="{{ $testimonial->language === 'fr' ? 'blue' : 'green' }}"
                            size="sm"
                        >
                            {{ $testimonial->language === 'fr' ? __('French') : __('English') }}
                        </flux:badge>
                    </flux:table.cell>
                    
                    <flux:table.cell>
                        <flux:switch
                            wire:click="toggleActive({{ $testimonial->id }})"
                            :checked="$testimonial->is_active"
                            size="sm"
                        />
                    </flux:table.cell>
                    
                    <flux:table.cell>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ $testimonial->order }}</flux:text>
                    </flux:table.cell>
                    
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            @can('edit-testimonials')
                                <flux:button
                                    href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                    variant="ghost"
                                    size="sm"
                                    icon="pencil"
                                    tooltip="{{ __('Edit') }}"
                                />
                            @endcan
                            
                            @can('delete-testimonials')
                                <flux:button
                                    wire:click="deleteTestimonial({{ $testimonial->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this testimonial?') }}"
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    tooltip="{{ __('Delete') }}"
                                    class="text-red-600 hover:text-red-700"
                                />
                            @endcan
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="7" class="text-center py-8">
                        <x-empty-state 
                            heading="{{ __('No testimonials found') }}"
                            description="{{ __('Get started by creating your first testimonial.') }}"
                        />
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <!-- Pagination -->
    @if($testimonials->hasPages())
        <div class="mt-6">
            {{ $testimonials->links() }}
        </div>
    @endif
</div> 