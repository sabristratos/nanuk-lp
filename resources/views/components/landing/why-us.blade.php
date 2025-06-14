<section id="pourquoi-nous" class="w-full overflow-hidden" data-element-key="why-us.section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <h2 data-content-key="why-us.title" class="why-us-title text-3xl sm:text-4xl md:text-5xl font-bold text-zinc-100 mb-12 md:mb-16 text-center">
            Pourquoi choisir Nanuk Web ?
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-x-8 gap-y-10 max-w-4xl mx-auto">
            <div class="why-us-item" data-element-key="why-us.item-1">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary-700 text-zinc-900">
                            <flux:icon.computer-desktop class="h-6 w-6" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 data-content-key="why-us.item-1.title" class="text-xl font-semibold text-zinc-100">
                            100+ campagnes Meta et Google optimisées
                        </h3>
                        <p data-content-key="why-us.item-1.description" class="mt-2 text-base text-zinc-100">
                            Spécialement conçues pour les PME locales, générant des résultats concrets.
                        </p>
                    </div>
                </div>
            </div>

            <div class="why-us-item" data-element-key="why-us.item-2">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary-700 text-zinc-900">
                            <flux:icon.bolt class="h-6 w-6" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 data-content-key="why-us.item-2.title" class="text-xl font-semibold text-zinc-100">
                            Approche stratégique, pas juste opérationnelle
                        </h3>
                        <p data-content-key="why-us.item-2.description" class="mt-2 text-base text-zinc-100">
                            Nous analysons vos besoins pour créer des stratégies sur mesure, pas des solutions toutes faites.
                        </p>
                    </div>
                </div>
            </div>

            <div class="why-us-item" data-element-key="why-us.item-3">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary-700 text-zinc-900">
                             <flux:icon.chat-bubble-left-ellipsis class="h-6 w-6" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 data-content-key="why-us.item-3.title" class="text-xl font-semibold text-zinc-100">
                            Explication claire, sans jargon ni pression
                        </h3>
                        <p data-content-key="why-us.item-3.description" class="mt-2 text-base text-zinc-100">
                            Nous communiquons ouvertement et vous aidons à comprendre chaque étape.
                        </p>
                    </div>
                </div>
            </div>

            <div class="why-us-item" data-element-key="why-us.item-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary-700 text-zinc-900">
                            <flux:icon.check-circle class="h-6 w-6" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 data-content-key="why-us.item-4.title" class="text-xl font-semibold text-zinc-100">
                            Aucune obligation d'achat après l'appel
                        </h3>
                        <p data-content-key="why-us.item-4.description" class="mt-2 text-base text-zinc-100">
                            Notre consultation gratuite est là pour vous éclairer, sans engagement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.from(".why-us-title", {
            scrollTrigger: {
                trigger: ".why-us-title",
                start: "top 85%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 0.8,
            ease: "power2.out"
        });

        const items = gsap.utils.toArray('.why-us-item');
        items.forEach((item, index) => {
            gsap.from(item, {
                scrollTrigger: {
                    trigger: item,
                    start: "top 85%", // Animate when item is 85% from top of viewport
                    toggleActions: "play none none none"
                },
                opacity: 0,
                y: 30,
                duration: 0.6,
                ease: "power2.out",
                delay: 0.2 + index * 0.15 // Stagger the animation of each item
            });
        });
    } else {
        console.warn('GSAP or ScrollTrigger is not defined. Animations for why-us section will not run.');
    }
});
</script> 