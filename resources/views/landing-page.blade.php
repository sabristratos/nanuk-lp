<x-layouts.frontend :title="'Faites Décoller Vos Pubs en Ligne | Nanuk Web'">
    <div class="overflow-x-hidden">
        {{-- Hero Section --}}
        <x-landing.hero />

        {{-- Section 1: Explanation --}}
        <x-landing.explanation />

        {{-- Section 2: Consultation Details --}}
        <x-landing.consultation-details />

        {{-- Section 3: Target Audience --}}
        <x-landing.target-audience />

        {{-- Section 5: Why Us --}}
        <x-landing.why-us />

        {{-- Optional: Urgency Banner --}}
        <x-landing.urgency-banner />

        {{-- Popup Form (Livewire Component) --}}
        @livewire('landing-page-form', [
            'experimentId' => $experimentData['experiment']?->id,
            'variationId' => $experimentData['variation']?->id
        ])
    </div>
</x-layouts.frontend> 