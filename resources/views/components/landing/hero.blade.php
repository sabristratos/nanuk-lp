@props([
    'experimentData' => null,
])

<section id="hero"
         class="w-full"
         x-data="experimentManager(@js($experimentData), 'landing.hero')"
         :class="variationClasses"
         data-element-key="hero.section"
>
    <div {{ $attributes }}>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20 md:py-32 flex flex-col items-center">
            <h1 class="hero-h1 text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-white"
                data-content-key="hero.title"
            >
                <span class="block">LE PLAN POUR FAIRE DÉCOLLER</span><span class="block text-primary-400 mt-1 md:mt-2">VOS PUBS EN LIGNE</span>
            </h1>
            <p class="hero-p1 mt-6 text-lg sm:text-xl md:text-2xl text-zinc-100 max-w-3xl mx-auto"
               data-content-key="hero.subtitle">
                Notre approche éprouvée pour aider les PME à générer plus de clients grâce à Google Ads et Meta Ads.
            </p>
            <p class="hero-p2 mt-8 text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-100 max-w-4xl mx-auto"
                data-content-key="hero.description">
                🚀 En 30 minutes, découvrez comment nos stratégies publicitaires personnalisées permettent à des entreprises comme la vôtre de générer des leads qualifiés… sans exploser leur budget.
            </p>
            <div class="hero-cta mt-12">
                <x-cta-button
                    dispatch="openModal"
                    class="px-10 py-4 text-lg"
                    wire:click="$dispatch('trackConversion', { experimentId: {{ $experimentData['experiment']['id'] ?? 'null' }}, variationId: {{ $experimentData['variation']['id'] ?? 'null' }} })"
                    data-content-key="hero.cta"
                >
                    RÉSERVER UNE CONSULTATION GRATUITE
                </x-cta-button>
            </div>

            <x-landing.animated-chart />
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined') {
        const heroTimeline = gsap.timeline({
            defaults: { opacity: 0, ease: "power2.out" }
        });

        heroTimeline.from(".hero-h1", { y: 50, duration: 0.8, delay: 0.2 })
                    .from(".hero-p1", { y: 40, duration: 0.7 }, "-=0.5")
                    .from(".hero-p2", { y: 30, duration: 0.6 }, "-=0.4")
                    .from(".hero-cta", { scale: 0.8, duration: 0.8, ease: "elastic.out(1, 0.75)" }, "-=0.3");
        
    } else {
        console.warn('GSAP is not defined. Animations for hero section will not run.');
    }
});
</script> 