<div>
    <flux:heading size="xl">{{ __('Submissions') }}</flux:heading>

    <div class="mt-6">
        <flux:table :paginate="$submissions">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$this->sortBy === 'first_name'" :direction="$this->sortDirection" wire:click="sort('first_name')">
                    {{ __('First Name') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$this->sortBy === 'last_name'" :direction="$this->sortDirection" wire:click="sort('last_name')">
                    {{ __('Last Name') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$this->sortBy === 'email'" :direction="$this->sortDirection" wire:click="sort('email')">
                    {{ __('Email') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$this->sortBy === 'created_at'" :direction="$this->sortDirection" wire:click="sort('created_at')">
                    {{ __('Date') }}
                </flux:table.column>
                <flux:table.column sortable :sorted="$this->sortBy === 'experiment.name'" :direction="$this->sortDirection" wire:click="sort('experiment.name')">
                    {{ __('Experiment') }}
                </flux:table.column>
                <flux:table.column>
                    <span class="sr-only">{{ __('Actions') }}</span>
                </flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($submissions as $submission)
                    <flux:table.row :key="$submission->id">
                        <flux:table.cell>
                            {{ $submission->first_name }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $submission->last_name }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $submission->email }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $submission->created_at->format('M d, Y H:i') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $submission->experiment?->name ?? __('N/A') }}
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <flux:button size="sm" variant="ghost" icon="eye" :href="route('admin.submissions.show', $submission)" />
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6">
                            <x-empty-state icon="document-text" heading="{{ __('No submissions yet') }}" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
