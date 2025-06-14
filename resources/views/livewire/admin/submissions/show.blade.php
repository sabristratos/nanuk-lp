<div>
    <div class="flex justify-between items-center mb-4">
        <flux:heading size="xl">
            {{ __('Submission Details') }}
        </flux:heading>
        <flux:button :href="route('admin.submissions.index')" variant="outline" icon="arrow-left">
            {{ __('Back to Submissions') }}
        </flux:button>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('Submission Data') }}</flux:heading>
                
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                     @if ($result->payload)
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            @foreach ($result->payload as $key => $value)
                                <div class="col-span-1">
                                    <flux:text variant="strong" class="text-sm">{{ Str::headline($key) }}</flux:text>
                                    <flux:text class="mt-1 text-sm">{{ is_bool($value) ? ($value ? 'Yes' : 'No') : ($value ?: 'N/A') }}</flux:text>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <x-empty-state heading="{{ __('No submission data') }}" message="{{ __('This submission does not have any payload data associated with it.') }}" />
                    @endif
                </div>
            </flux:card>
        </div>
        <div class="space-y-6">
             <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('Metadata') }}</flux:heading>

                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                    <dl>
                        <div>
                            <flux:text variant="strong" class="text-sm">{{ __('Visitor ID') }}</flux:text>
                            <flux:text class="mt-1 font-mono text-sm">{{ $result->visitor_id }}</flux:text>
                        </div>
                        <div class="mt-4">
                            <flux:text variant="strong" class="text-sm">{{ __('Submission Date') }}</flux:text>
                            <flux:text class="mt-1 text-sm">{{ $result->created_at->format('M d, Y, H:i:s') }}</flux:text>
                        </div>
                    </dl>
                </div>
            </flux:card>
             <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('A/B Test Details') }}</flux:heading>

                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                     <dl>
                        <div>
                            <flux:text variant="strong" class="text-sm">{{ __('Experiment') }}</flux:text>
                            <flux:text class="mt-1 text-sm">{{ $result->experiment->name }}</flux:text>
                        </div>
                        <div class="mt-4">
                            <flux:text variant="strong" class="text-sm">{{ __('Variation') }}</flux:text>
                            <flux:text class="mt-1 text-sm">{{ $result->variation->name }}</flux:text>
                        </div>
                    </dl>
                </div>
            </flux:card>
        </div>
    </div>
</div>
