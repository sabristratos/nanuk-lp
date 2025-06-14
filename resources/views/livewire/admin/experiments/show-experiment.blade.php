<div>
    <div class="flex justify-between items-center mb-4">
        <flux:heading size="xl">
            {{ __('Experiment Results: ') . $experiment->name }}
        </flux:heading>
        <flux:button :href="route('admin.experiments.index')" variant="outline" icon="arrow-left" tooltip="{{ __('Back to Experiments') }}">
            {{ __('Back to Experiments') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" class="my-8" />

    <flux:card class="space-y-6">
        <div>
            <flux:heading>{{ __('Conversion Rate Over Time') }}</flux:heading>
        </div>
        <flux:chart :value="$chartData" class="aspect-[3/1]">
            <flux:chart.svg>
                @foreach ($experiment->variations as $index => $variation)
                    <g data-variation>
                        <flux:chart.line field="variation_{{ $index }}_conversions" class="text-[--tw-variation-color]" curve="smooth" />
                        <flux:chart.area field="variation_{{ $index }}_conversions" class="text-[--tw-variation-color]/10" curve="smooth" />
                    </g>
                @endforeach

                <flux:chart.axis axis="x" field="date" :format="['month' => 'short', 'day' => 'numeric']">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>

                <flux:chart.cursor />
            </flux:chart.svg>
            <flux:chart.tooltip>
                <flux:chart.tooltip.heading field="date" :format="['year' => 'numeric', 'month' => 'long', 'day' => 'numeric']" />
                 @foreach ($experiment->variations as $index => $variation)
                    <flux:chart.tooltip.value field="variation_{{ $index }}_conversions" label="{{ $variation->name }}" />
                @endforeach
            </flux:chart.tooltip>
        </flux:chart>
    </flux:card>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @foreach ($experiment->variations as $variation)
            <flux:card class="space-y-4">
                <div>
                    <flux:heading size="md">{{ $variation->name }}</flux:heading>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ $variation->description }}</p>
                    <div class="mt-4 space-y-1">
                        @php
                            $totalConversions = $variation->results->count();
                            $uniqueConvertingVisitors = $variation->results()->distinct('visitor_id')->count();
                            $views = $variation->views()->distinct('visitor_id')->count();
                            $conversionRate = $views > 0 ? ($uniqueConvertingVisitors / $views) * 100 : 0;
                        @endphp
                        <p><strong>{{ __('Unique Visitors:') }}</strong> {{ $views }}</p>
                        <p><strong>{{ __('Unique Converting Visitors:') }}</strong> {{ $uniqueConvertingVisitors }}</p>
                        <p><strong>{{ __('Total Conversions:') }}</strong> {{ $totalConversions }}</p>
                        <p class="font-bold pt-2"><strong>{{ __('Conversion Rate:') }}</strong> {{ number_format($conversionRate, 2) }}%</p>
                    </div>
                </div>
            </flux:card>
        @endforeach
    </div>
</div> 