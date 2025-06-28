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
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                        @php
                            $fields = [
                                'first_name' => 'First Name',
                                'last_name' => 'Last Name',
                                'email' => 'Email',
                                'phone' => 'Phone',
                                'website' => 'Website',
                                'business_years' => 'Years in Business',
                                'main_objective' => 'Main Objective',
                                'online_advertising_experience' => 'Online Advertising Experience',
                                'monthly_budget' => 'Monthly Budget',
                                'ready_to_invest' => 'Ready to Invest',
                                'consent' => 'Consent',
                            ];
                        @endphp

                        @foreach ($fields as $field => $label)
                            <div class="col-span-1">
                                <flux:text variant="strong" class="text-sm">{{ $label }}</flux:text>
                                <flux:text class="mt-1 text-sm">
                                    @if(is_bool($submission->{$field}))
                                        {{ $submission->{$field} ? 'Yes' : 'No' }}
                                    @else
                                        {{ $submission->{$field} ?: 'N/A' }}
                                    @endif
                                </flux:text>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </flux:card>
        </div>
        <div class="space-y-6">
             <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('Metadata') }}</flux:heading>

                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                    <dl>
                        <div>
                            <flux:text variant="strong" class="text-sm">{{ __('IP Address') }}</flux:text>
                            <flux:text class="mt-1 font-mono text-sm">{{ $submission->ip_address }}</flux:text>
                        </div>
                        <div class="mt-4">
                            <flux:text variant="strong" class="text-sm">{{ __('Submission Date') }}</flux:text>
                            <flux:text class="mt-1 text-sm">{{ $submission->created_at->format('M d, Y, H:i:s') }}</flux:text>
                        </div>
                        <div class="mt-4">
                            <flux:text variant="strong" class="text-sm">{{ __('User Agent') }}</flux:text>
                            <flux:text class="mt-1 text-sm">{{ $submission->user_agent }}</flux:text>
                        </div>
                    </dl>
                </div>
            </flux:card>
             @if($submission->experiment)
                <flux:card class="space-y-4">
                    <flux:heading size="lg">{{ __('A/B Test Details') }}</flux:heading>

                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                        <dl>
                            <div>
                                <flux:text variant="strong" class="text-sm">{{ __('Experiment') }}</flux:text>
                                <flux:text class="mt-1 text-sm">{{ $submission->experiment->name }}</flux:text>
                            </div>
                            @if($submission->variation)
                                <div class="mt-4">
                                    <flux:text variant="strong" class="text-sm">{{ __('Variation') }}</flux:text>
                                    <flux:text class="mt-1 text-sm">{{ $submission->variation->name }}</flux:text>
                                </div>
                            @endif
                        </dl>
                    </div>
                </flux:card>
             @endif
        </div>
    </div>
</div>
