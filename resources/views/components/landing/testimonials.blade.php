@props([
    'experimentData' => null,
])

@php
    $testimonials = app(\App\Services\TestimonialService::class)->getActiveTestimonials(app()->getLocale());
@endphp

@if($testimonials->isNotEmpty())
<div data-element-key="testimonials.container" 
     class="relative w-full max-w-4xl mx-auto mt-16 md:mt-20"
     x-data="experimentManager(@js($experimentData), 'landing.testimonials')"
     :class="variationClasses">
    
    <!-- Background Glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-primary-500/20 rounded-full mix-blend-lighten filter blur-3xl opacity-0 testimonials-glow"></div>
    
    <!-- Testimonials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
        @foreach($testimonials as $testimonial)
            <div class="testimonial-card glass-effect p-6 rounded-2xl border border-zinc-700/50 opacity-0" 
                 data-element-key="testimonials.card.{{ $testimonial->id }}">
                <p data-content-key="testimonials.{{ $testimonial->id }}.quote" class="text-zinc-200 text-sm leading-relaxed text-left">
                    "{{ $testimonial->quote }}"
                </p>
                <div class="mt-4 flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <flux:icon name="star" variant="solid" class="w-4 h-4 text-yellow-400" />
                    @endfor
                </div>
                @if($testimonial->author_name || $testimonial->company_name || $testimonial->position)
                    <div class="mt-3 pt-3 border-t border-zinc-700/30 text-left">
                        @if($testimonial->author_name)
                            <p class="text-zinc-100 text-sm font-medium text-left">{{ $testimonial->author_name }}</p>
                        @endif
                        @if($testimonial->position || $testimonial->company_name)
                            <p class="text-zinc-400 text-xs text-left">
                                @if($testimonial->position && $testimonial->company_name)
                                    {{ $testimonial->position }} chez {{ $testimonial->company_name }}
                                @elseif($testimonial->position)
                                    {{ $testimonial->position }}
                                @elseif($testimonial->company_name)
                                    {{ $testimonial->company_name }}
                                @endif
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
    /* Glassmorphism effect consistent with dark theme */
    .glass-effect {
        background: rgba(var(--color-zinc-800-rgb, 39 39 42) / 0.3);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(var(--color-zinc-700-rgb, 55 65 81) / 0.5);
        transition: all 0.3s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-2px);
        border-color: rgba(var(--color-zinc-600-rgb, 82 82 91) / 0.7);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof gsap === 'undefined') {
        console.warn('GSAP is not defined. Animations for testimonials component will not run.');
        return;
    }

    const componentRoot = document.querySelector('[data-element-key="testimonials.container"]');
    if (!componentRoot) return;

    const glow = componentRoot.querySelector('.testimonials-glow');
    const testimonialCards = componentRoot.querySelectorAll('.testimonial-card');

    // Set initial states
    gsap.set(glow, { opacity: 0, scale: 0.8 });
    gsap.set(testimonialCards, { opacity: 0, y: 30 });

    const observer = new IntersectionObserver((entries, observerInstance) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const timeline = gsap.timeline();

                // Background glow animation
                timeline.to(glow, {
                    opacity: 0.4,
                    scale: 1,
                    duration: 2,
                    ease: "power2.out"
                }, 0);

                // Testimonial cards staggered entrance
                timeline.to(testimonialCards, {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    ease: "power2.out",
                    stagger: 0.15
                }, 0.3);

                // Continuous subtle glow animation
                gsap.to(glow, {
                    opacity: 0.6,
                    scale: 1.05,
                    duration: 4,
                    repeat: -1,
                    yoyo: true,
                    ease: "sine.inOut"
                });

                observerInstance.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    });

    observer.observe(componentRoot);
});
</script>
@endif 