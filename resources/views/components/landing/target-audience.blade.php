<section id="pour-qui" class="w-full overflow-hidden" data-element-key="target-audience.section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <h2 data-content-key="target-audience.title" class="target-audience-title text-3xl sm:text-4xl md:text-5xl font-bold text-zinc-100 mb-12 md:mb-16 text-center">
            Pour qui est-ce ?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div data-element-key="target-audience.item-1" class="target-audience-item bg-white/5 p-6 rounded-lg border-2 border-secondary-600 border-b-[6px] hover:-rotate-2 transition-transform duration-200 ease-in-out">
                <div class="flex items-start">
                    <flux:icon.briefcase class="flex-shrink-0 size-7 text-primary-500 mr-4 mt-1" />
                    <p data-content-key="target-audience.item-1.text" class="text-lg md:text-xl text-zinc-100">
                        Propriétaires de PME qui veulent plus de clients sans perdre de temps
                    </p>
                </div>
            </div>
            <div data-element-key="target-audience.item-2" class="target-audience-item bg-white/5 p-6 rounded-lg border-2 border-secondary-600 border-b-[6px] hover:-rotate-2 transition-transform duration-200 ease-in-out">
                <div class="flex items-start">
                    <flux:icon.sparkles class="flex-shrink-0 size-7 text-primary-500 mr-4 mt-1" />
                    <p data-content-key="target-audience.item-2.text" class="text-lg md:text-xl text-zinc-100">
                        Entrepreneurs qui souhaitent une agence qui tient ses engagements et travaille main dans la main avec vous.
                    </p>
                </div>
            </div>
            <div data-element-key="target-audience.item-3" class="target-audience-item bg-white/5 p-6 rounded-lg border-2 border-secondary-600 border-b-[6px] hover:-rotate-2 transition-transform duration-200 ease-in-out">
                <div class="flex items-start">
                    <flux:icon.user-group class="flex-shrink-0 size-7 text-primary-500 mr-4 mt-1" />
                    <p data-content-key="target-audience.item-3.text" class="text-lg md:text-xl text-zinc-100">
                        Entreprises qui veulent enfin optimiser leurs pubs et leur retour sur investissement.
                    </p>
                </div>
            </div>
            <div data-element-key="target-audience.item-4" class="target-audience-item bg-white/5 p-6 rounded-lg border-2 border-secondary-600 border-b-[6px] hover:-rotate-2 transition-transform duration-200 ease-in-out">
                <div class="flex items-start">
                     <flux:icon.exclamation-circle class="flex-shrink-0 size-7 text-primary-500 mr-4 mt-1" />
                    <p data-content-key="target-audience.item-4.text" class="text-lg md:text-xl text-zinc-100">
                        Entrepreneurs frustrés de payer des pubs qui ne livrent rien
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.from(".target-audience-title", {
            scrollTrigger: {
                trigger: ".target-audience-title",
                start: "top 85%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 0.8,
            ease: "power2.out"
        });

        const items = gsap.utils.toArray('.target-audience-item');
        items.forEach((item, index) => {
            gsap.from(item, {
                scrollTrigger: {
                    trigger: item,
                    start: "top 85%",
                    toggleActions: "play none none none"
                },
                opacity: 0,
                y: 40,
                scale: 0.95,
                duration: 0.6,
                ease: "power2.out",
                delay: 0.2 + index * 0.15 // Stagger items
            });
        });
    } else {
        console.warn('GSAP or ScrollTrigger is not defined. Animations for target audience section will not run.');
    }
});
</script> 