<section id="appel" class="w-full overflow-hidden" data-element-key="consultation-details.section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="consultation-box max-w-3xl mx-auto bg-white/5 rounded-xl p-8 md:p-12" data-element-key="consultation-details.box">
            <h2 data-content-key="consultation-details.title" class="consultation-title text-2xl sm:text-3xl md:text-4xl font-bold text-zinc-100 mb-8 text-center">
                Durant cette consultation gratuite, on va analyser ensemble :
            </h2>
            <ul class="consultation-list space-y-4 text-lg md:text-xl text-zinc-100">
                <li class="consultation-list-item flex items-start">
                    <flux:icon.check-circle class="flex-shrink-0 w-6 h-6 text-primary-500 mr-3 mt-1" />
                    <span data-content-key="consultation-details.item-1">Vos objectifs marketing à court et moyen terme</span>
                </li>
                <li class="consultation-list-item flex items-start">
                    <flux:icon.check-circle class="flex-shrink-0 w-6 h-6 text-primary-500 mr-3 mt-1" />
                    <span data-content-key="consultation-details.item-2">Vos campagnes actuelles (si vous en avez) ou vos opportunités inexploitées</span>
                </li>
                <li class="consultation-list-item flex items-start">
                    <flux:icon.check-circle class="flex-shrink-0 w-6 h-6 text-primary-500 mr-3 mt-1" />
                    <span data-content-key="consultation-details.item-4">Votre budget optimal pour des résultats concrets</span>
                </li>
                <li class="consultation-list-item flex items-start">
                    <flux:icon.star variant="solid" class="flex-shrink-0 w-6 h-6 text-amber-400 mr-3 mt-1" />
                    <span data-content-key="consultation-details.item-5" class="font-semibold text-transparent bg-clip-text bg-gradient-to-r from-primary-300 via-primary-400 to-primary-300">Et surtout : si la publicité est vraiment le bon levier pour votre entreprise en ce moment</span>
                </li>
            </ul>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        const consultationBox = document.querySelector('.consultation-box');
        if (consultationBox) {
            gsap.from(consultationBox, {
                scrollTrigger: {
                    trigger: consultationBox,
                    start: "top 80%",
                    toggleActions: "play none none none"
                },
                opacity: 0,
                y: 50,
                scale: 0.95,
                duration: 0.8,
                ease: "power2.out"
            });

            const listItems = gsap.utils.toArray('.consultation-list-item');
            listItems.forEach((item, index) => {
                gsap.from(item, {
                    scrollTrigger: {
                        trigger: consultationBox, // Trigger all list items when the box itself is visible
                        start: "top 75%", // Adjust start point if needed
                        toggleActions: "play none none none"
                    },
                    opacity: 0,
                    x: -30,
                    duration: 0.6,
                    ease: "power2.out",
                    delay: 0.5 + index * 0.15 // Stagger list items after box animation
                });
            });
        }
    } else {
        console.warn('GSAP or ScrollTrigger is not defined. Animations for consultation details section will not run.');
    }
});
</script> 