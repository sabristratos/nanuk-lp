<x-layouts.frontend :title="'Faites Décoller Vos Pubs en Ligne | Nanuk Web'">
    <div class="overflow-x-hidden">
        @php
            $formModification = collect($experimentData['modifications'] ?? [])->firstWhere('type', 'component');
            $formDisplay = $formModification['payload']['show'] ?? 'modal';
            $formPosition = $formModification['payload']['position'] ?? 'end';
        @endphp

        {{-- Hero Section --}}
        <x-landing.hero />

        @if($formDisplay === 'embedded' && $formPosition === 'hero')
            <div id="form-section" data-element-key="form.section">
                @livewire('landing-page-form', [
                    'experimentId' => $experimentData['experiment']?->id,
                    'variationId' => $experimentData['variation']?->id,
                    'displayMode' => 'embedded'
                ])
            </div>
        @endif

        {{-- Section 1: Explanation --}}
        <x-landing.explanation />

        {{-- Section 2: Consultation Details --}}
        <x-landing.consultation-details />

        {{-- Section 3: Target Audience --}}
        <x-landing.target-audience />

        {{-- Section 5: Why Us --}}
        <x-landing.why-us />

        @if($formDisplay === 'embedded' && $formPosition === 'end')
            <div id="form-section" data-element-key="form.section">
                @livewire('landing-page-form', [
                    'experimentId' => $experimentData['experiment']?->id,
                    'variationId' => $experimentData['variation']?->id,
                    'displayMode' => 'embedded'
                ])
            </div>
        @endif

        {{-- Optional: Urgency Banner --}}
        <x-landing.urgency-banner />

        {{-- Popup Form (Livewire Component) --}}
        @if($formDisplay === 'modal')
            @livewire('landing-page-form', [
                'experimentId' => $experimentData['experiment']?->id,
                'variationId' => $experimentData['variation']?->id,
                'displayMode' => 'modal'
            ])
        @endif
    </div>
</x-layouts.frontend> 