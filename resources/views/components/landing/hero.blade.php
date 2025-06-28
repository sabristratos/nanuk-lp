@props([
    'experimentData' => null,
])

@php
    use App\Facades\Settings;
    $heroVideoUrl = Settings::get('hero_video_url');
    $heroImage = Settings::get('hero_image');
    // Extract YouTube video ID if present
    $youtubeId = null;
    if ($heroVideoUrl) {
        if (preg_match('/(?:youtu.be\/|youtube.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $heroVideoUrl, $matches)) {
            $youtubeId = $matches[1];
        }
    }
@endphp

<section id="hero"
         class="w-full"
         x-data="experimentManager(@js($experimentData), 'landing.hero')"
         :class="variationClasses"
         data-element-key="hero.section"
>
    <div {{ $attributes }}>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 md:pt-32">
            <div class="flex flex-col justify-center items-center text-center">
                <h1 class="hero-h1 text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-white"
                    data-content-key="hero.title"
                >
                    <span class="block">LE PLAN POUR FAIRE DÉCOLLER</span><span class="block text-primary-400 mt-1 md:mt-2">VOS PUBS EN LIGNE</span>
                </h1>
                <p class="hero-p1 mt-6 text-lg sm:text-xl md:text-2xl text-zinc-100 max-w-4xl mx-auto"
                   data-content-key="hero.subtitle">
                    Notre approche éprouvée pour aider les PME à générer davantage de clients, notamment grâce à Google Ads et Meta Ads.
                </p>
                
                @if($youtubeId || $heroImage)
                    <!-- Media positioned between headline and second paragraph -->
                    <div class="w-full flex justify-center items-center my-12" data-element-key="hero.media">
                        @if($youtubeId)
                            <div class="aspect-video w-full max-w-4xl rounded-2xl overflow-hidden shadow-lg bg-black/70" data-element-key="hero.video">
                                <iframe
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&showinfo=0"
                                    title="Hero Video"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    class="w-full h-full"
                                    data-element-key="hero.video.iframe"
                                ></iframe>
                            </div>
                        @elseif($heroImage)
                            <img src="{{ $heroImage }}" alt="Hero Image" class="aspect-video w-full max-w-4xl rounded-2xl object-cover shadow-lg bg-black/70" loading="lazy" data-element-key="hero.image" />
                        @endif
                    </div>
                @endif
                
                <p class="hero-p2 mt-8 text-xl sm:text-2xl md:text-3xl font-semibold text-zinc-100 max-w-4xl mx-auto"
                    data-content-key="hero.description">
                    🚀 En 30 minutes, découvrez comment nos stratégies publicitaires personnalisées permettent à des entreprises comme la vôtre de générer des leads qualifiés… sans exploser leur budget.
                </p>
                <div class="hero-cta mt-12" data-element-key="hero.cta">
                    <x-cta-button
                        click="scrollToForm()"
                        class="px-10 py-4 text-lg"
                        data-content-key="hero.cta"
                    >
                        RÉSERVER UNE CONSULTATION GRATUITE
                    </x-cta-button>
                </div>
            </div>
            
            <!-- Testimonials (Always centered, outside the media/content layout) -->
            <x-landing.testimonials />
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