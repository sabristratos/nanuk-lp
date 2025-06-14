<div x-data="{
        bannerVisible: false,
        bannerVisibleAfter: 300
    }"
    x-show="bannerVisible"
    x-transition:enter="transition ease-out duration-500 transform"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    x-init="setTimeout(() => { bannerVisible = true }, bannerVisibleAfter)"
    class="fixed bottom-0 left-0 right-0 p-4 z-50 pointer-events-none"
    x-cloak
    aria-live="assertive"
    data-element-key="urgency-banner.container"
>
    <div class="pointer-events-auto max-w-2xl mx-auto bg-secondary-600 rounded-xl shadow-2xl p-4" data-element-key="urgency-banner.panel">
        <div class="flex items-center justify-between space-x-4">
            <div class="flex items-center space-x-3">
                <span class="flex-shrink-0">
                    <flux:icon.rocket-launch class="w-6 h-6 text-white" />
                </span>
                <span data-content-key="urgency-banner.text" class="block text-base leading-tight text-white">
                    ⚠️ Ce mois-ci, nous offrons <span class="font-semibold underline">uniquement 10 consultations gratuites</span> aux entreprises sélectionnées. Réservez la vôtre avant que les places soient comblées.
                </span>
            </div>
            <button @click="bannerVisible = false"
                    class="flex-shrink-0 p-1.5 text-zinc-200 rounded-full hover:bg-white/10 hover:text-white transition-colors duration-150 ease-out focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-secondary-600">
                <span data-content-key="urgency-banner.dismiss-text" class="sr-only">Dismiss</span>
                <flux:icon.x-mark class="w-5 h-5" />
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        const banner = document.querySelector('.urgency-banner-section');
        if (banner) {
            gsap.from(banner, {
                scrollTrigger: {
                    trigger: banner,
                    start: "top 95%",
                    toggleActions: "play none none none"
                },
                opacity: 0,
                y: -30,
                duration: 0.7,
                ease: "power2.out"
            });

           
            gsap.to(".urgency-text", {
                opacity: 0.7,
                duration: 1,
                repeat: -1, 
               yoyo: true, 
                ease: "sine.inOut",
                delay: 1 
         });
        }
    } else {
        console.warn('GSAP or ScrollTrigger is not defined. Animations for urgency banner will not run.');
    }
});
</script> 