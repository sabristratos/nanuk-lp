<div>
    <flux:heading size="xl">{{ __('Submissions') }}</flux:heading>

    <div class="mt-6">
        <flux:table :paginate="$results">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$this->sortBy === 'experiment.name'" :direction="$this->sortDirection" wire:click="sort('experiment.name')">
                    {{ __('Experiment') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$this->sortBy === 'variation.name'" :direction="$this->sortDirection" wire:click="sort('variation.name')">
                    {{ __('Variation') }}
                </flux:table.column>
                <flux:table.column>
                    {{ __('Visitor ID') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$this->sortBy === 'conversion_type'" :direction="$this->sortDirection" wire:click="sort('conversion_type')">
                    {{ __('Type') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$this->sortBy === 'created_at'" :direction="$this->sortDirection" wire:click="sort('created_at')">
                    {{ __('Date') }}
                </flux:table.column>
                <flux:table.column>
                    <span class="sr-only">{{ __('Actions') }}</span>
                </flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($results as $result)
                    <flux:table.row :key="$result->id">
                        <flux:table.cell>
                            {{ $result->experiment?->name ?? __('N/A') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $result->variation?->name ?? __('N/A') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="font-mono text-xs">{{ $result->visitor_id }}</span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="blue">{{ $result->conversion_type }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $result->created_at->format('M d, Y H:i') }}
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <flux:button size="sm" variant="ghost" icon="eye" :href="route('admin.submissions.show', $result)" />
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5">
                            <x-empty-state icon="document-text" heading="{{ __('No submissions yet') }}" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
