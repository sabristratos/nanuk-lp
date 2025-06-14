@props([
    'href' => null,
    'type' => 'button',
    'dispatch' => null,
    'click' => null, // For general Alpine/JS click handlers
    'dispatchParams' => [], // For Livewire dispatch parameters
    'wireLoadingTarget' => null // New prop for wire:target on loading spinner
])

@php
    $tag = $href ? 'a' : 'button';

    $baseClasses = [
        'group',
        'relative',
        'inline-flex',
        'items-center',
        'justify-center',
        'px-8', // Default horizontal padding (2rem)
        'py-3', // Default vertical padding (0.75rem)
        'rounded-full',
        'font-bold',
        'text-black',
        'bg-primary-400',
        'focus:outline-none',
        'focus:ring-4',
        'focus:ring-primary-300',
        'transition-all',
        'duration-300',
        'ease-in-out',
        'overflow-hidden',
    ];

    // Add hover styles and cursor only if not in a loading state managed by wire:target
    // The actual disabling and opacity for loading is handled by wire:loading attributes below
    if (!$attributes->has('wire:target') && !$attributes->has('wire:click')) {
        $baseClasses[] = 'hover:bg-white';
        $baseClasses[] = 'hover:-translate-y-0.5';
    } else if ($attributes->has('wire:target') || $attributes->has('wire:click')){
        // For Livewire buttons, hover is conditional via wire:loading classes below
        // but we add the base interaction classes here if needed
        $baseClasses[] = 'group-hover:bg-white';
        $baseClasses[] = 'group-hover:-translate-y-0.5';
    }
@endphp

<{{ $tag }}
    @if ($tag === 'a' && $href) href="{{ $href }}" @endif
    @if ($tag === 'button')
        type="{{ $type }}"
        @if ($attributes->has('wire:target') || $attributes->has('wire:click')) wire:loading.attr="disabled" @endif
    @endif
    @if ($dispatch)
        @php
            $paramsString = count($dispatchParams) > 0 ? ', ' . json_encode($dispatchParams) : '';
        @endphp
        @click.prevent="Livewire.dispatch('{{ $dispatch }}'{{ $paramsString }})"
    @endif
    @if (!$dispatch && $click) @click="{{ $click }}" @endif
    {{ $attributes->class($baseClasses)
        ->merge([
            ($attributes->has('wire:target') || $attributes->has('wire:click')) ? 'wire:loading:opacity-75 wire:loading:cursor-wait' : ''
        ]) }}
    @if($wireLoadingTarget) wire:loading.attr="disabled" wire:target="{{ $wireLoadingTarget }}" @else wire:loading.attr="disabled" @endif
>
    <span class="flex items-center justify-center" @if($attributes->has('wire:target') || $attributes->has('wire:click')) wire:loading.remove @endif >
        <!-- Dot & Arrow Animated Element -->
        <span aria-hidden="true" class="icon-wrapper absolute left-4 top-1/2 transform -translate-y-1/2 flex items-center justify-center w-6 h-6 p-1 rounded-full bg-black group-hover:bg-primary-400 scale-0 opacity-0 group-hover:scale-[1.6] group-hover:opacity-100 transition-all duration-300 ease-in-out delay-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="arrow-svg h-4 w-4 text-transparent group-hover:text-white transform scale-50 -translate-x-full group-hover:scale-100 group-hover:translate-x-0 transition-all duration-300 ease-in-out delay-100">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
            </svg>
        </span>

        <!-- Text Content -->
        <span class="text-content relative z-10 transition-transform duration-300 ease-in-out transform group-hover:translate-x-4">
            {{ $slot }}
        </span>
    </span>
    <span class="absolute inset-0 flex items-center justify-center" @if($attributes->has('wire:target') || $attributes->has('wire:click')) wire:loading @else style="display: none;" @endif wire:loading.delay.short>
        <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </span>
</{{ $tag }}> 