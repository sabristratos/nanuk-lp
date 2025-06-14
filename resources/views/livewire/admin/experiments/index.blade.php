<div>
    <div class="flex justify-between items-center mb-4">
        <flux:heading size="xl">{{ __('A/B Testing Management') }}</flux:heading>
        @can('create-experiments')
        <flux:button variant="primary" icon="plus" :href="route('admin.experiments.create')">{{ __('Create Experiment') }}</flux:button>
        @endcan
    </div>

    <flux:separator variant="subtle" class="my-8" />

    @if ($experiments->total() === 0 && !$this->hasFilters())
        <x-empty-state
            icon="beaker"
            heading="{{ __('No experiments yet') }}"
            description="{{ __('Get started by creating your first experiment.') }}">
            @can('create-experiments')
            <flux:button variant="primary" icon="plus" :href="route('admin.experiments.create')">{{ __('Create Experiment') }}</flux:button>
            @endcan
        </x-empty-state>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <flux:input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search...') }}" icon="magnifying-glass" />
            <flux:select wire:model.live="status">
                <option value="">{{ __('All Statuses') }}</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ str($status->name)->title() }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </flux:select>
        </div>
        @if($experiments->isEmpty())
            <x-empty-state
                icon="magnifying-glass"
                heading="{{ __('No experiments found') }}"
                description="{{ __('Try adjusting your search or filter to find what you\'re looking for.') }}"
            />
        @else
            <flux:table :paginate="$experiments">
                <flux:table.columns>
                    <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column>{{ __('Variations') }}</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy === 'start_date'" :direction="$sortDirection" wire:click="sort('start_date')">{{ __('Starts At') }}</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy === 'end_date'" :direction="$sortDirection" wire:click="sort('end_date')">{{ __('Ends At') }}</flux:table.column>
                    <flux:table.column />
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($experiments as $experiment)
                        <flux:table.row :key="$experiment->id">
                            <flux:table.cell>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $experiment->name }}</div>
                                <div class="text-sm text-gray-500">{{ str($experiment->description)->limit(50) }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge :variant="$experiment->status->color()">{{ $experiment->status->name }}</flux:badge>
                            </flux:table.cell>
                             <flux:table.cell>
                                {{ $experiment->variations_count }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $experiment->start_date?->format('Y-m-d') ?? 'N/A' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $experiment->end_date?->format('Y-m-d') ?? 'N/A' }}
                            </flux:table.cell>
                            <flux:table.cell class="text-right">
                                <flux:dropdown>
                                    <flux:button icon="ellipsis-vertical" variant="ghost" tooltip="{{ __('Actions') }}" />
                                    <flux:menu>
                                        @can('view-experiments')
                                        <flux:menu.item :href="route('admin.experiments.show', $experiment)" icon="chart-bar">{{ __('View Results') }}</flux:menu.item>
                                        @endcan
                                        @can('edit-experiments')
                                        <flux:menu.item :href="route('admin.experiments.edit', $experiment)" icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                        @endcan
                                        @can('delete-experiments')
                                        <flux:menu.item wire:click="$dispatch('confirm-delete-experiment', { experiment: {{ $experiment->id }} })" icon="trash">{{ __('Delete') }}</flux:menu.item>
                                        @endcan
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $experiments->links() }}
            </div>
        @endif
    @endif

    <flux:modal wire:model="confirmingDelete" class="md:w-96">
        @if ($deletingExperiment)
            <flux:heading size="lg">{{ __('Delete experiment: ') . $deletingExperiment->name }}</flux:heading>
            <p class="mt-2">{{ __('Are you sure you want to delete this experiment? This action cannot be undone.') }}</p>
            <div class="flex justify-end space-x-3 mt-8">
                <flux:button variant="outline" wire:click="$set('confirmingDelete', false)">{{ __('Cancel') }}</flux:button>
                <flux:button variant="danger" wire:click="delete">{{ __('Delete') }}</flux:button>
            </div>
        @endif
    </flux:modal>
</div> 