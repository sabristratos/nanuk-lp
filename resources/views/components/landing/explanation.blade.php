<section id="methode" class="w-full overflow-hidden" data-element-key="explanation.section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="max-w-4xl mx-auto text-center">
            <h2 data-content-key="explanation.title" class="explanation-title text-3xl sm:text-4xl md:text-5xl font-bold text-zinc-100 mb-6">
                Nous avons bâti une méthode pour rentabiliser chaque dollar investi en publicité.
            </h2>
        </div>
        <div class="max-w-4xl mx-auto">
            <p data-content-key="explanation.text-1" class="explanation-text-1 text-lg md:text-xl text-zinc-100 mb-6 text-center">
                Chez Nanuk Web, on aide les PME à transformer leurs publicités en véritable machine à générer des leads.
            </p>
            <p data-content-key="explanation.text-2" class="explanation-text-2 text-lg md:text-xl text-zinc-100 mb-6 text-center">
                Notre méthode repose sur une combinaison stratégique de ciblage, d'optimisation et de création de messages puissants — le tout adapté à votre réalité.
            </p>
            <p data-content-key="explanation.text-3" class="explanation-text-3 text-lg md:text-xl text-zinc-100 mb-6 text-center">
                La pub digitale, c'est devenu un champ de mines.
            </p>
            <div class="explanation-bullets mb-6 text-center">
                <p class="text-lg md:text-xl text-zinc-100 mb-2">✔️ La concurrence explose</p>
                <p class="text-lg md:text-xl text-zinc-100 mb-2">✔️ Les algorithmes changent sans prévenir</p>
                <p class="text-lg md:text-xl text-zinc-100 mb-4">✔️ Ce qui fonctionnait hier ne convertit plus aujourd'hui…</p>
            </div>
            <p data-content-key="explanation.text-4" class="explanation-text-4 text-lg md:text-xl text-zinc-100 mb-6 text-center">
                C'est pourquoi notre approche est à la fois technique, humaine et 100 % orientée performance.
            </p>
            <p data-content-key="explanation.text-5" class="explanation-text-5 text-lg md:text-xl text-zinc-50 mb-10 text-center font-semibold">
                Si vous voulez arrêter de gaspiller de l'argent dans des pubs qui ne convertissent pas, réservez votre appel avec un stratège Nanuk Web maintenant.
            </p>
            <div class="explanation-cta text-center">
                <x-cta-button dispatch="openModal" class="px-10 py-4 text-lg" data-content-key="explanation.cta">
                    JE VEUX PARLER À UN EXPERT
                </x-cta-button>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.utils.toArray(['explanation-title', '.explanation-text-1', '.explanation-text-2', '.explanation-text-3', '.explanation-text-4', '.explanation-cta']).forEach((elem, index) => {
            let delay = index * 0.15;
            if (elem.classList.contains('explanation-title')) {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 85%",
                        toggleActions: "play none none none",
                    },
                    opacity: 0,
                    y: 60,
                    duration: 0.8,
                    ease: "power2.out",
                    delay: delay
                });
            } else if (elem.classList.contains('explanation-cta')) {
                 gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 90%",
                        toggleActions: "play none none none",
                    },
                    opacity: 0,
                    scale: 0.8,
                    duration: 0.8,
                    ease: "elastic.out(1, 0.75)",
                    delay: delay
                });
            } else {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: "top 90%", // Start animation when element is 90% from the top of the viewport
                        toggleActions: "play none none none", // Play animation once when it enters
                    },
                    opacity: 0,
                    y: 40,
                    duration: 0.7,
                    ease: "power2.out",
                    delay: delay
                });
            }
        });
    } else {
        console.warn('GSAP or ScrollTrigger is not defined. Animations for explanation section will not run.');
    }
});
</script> 