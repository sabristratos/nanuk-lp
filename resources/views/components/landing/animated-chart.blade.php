<div data-element-key="animated-chart.container" class="relative w-full max-w-3xl mx-auto h-[500px] md:h-[550px] flex items-center justify-center mt-16 md:mt-20 pointer-events-none">

    <!-- Background Glows -->
    <div class="bg-glow absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 md:w-96 md:h-96 bg-primary-500/30 rounded-full mix-blend-lighten filter blur-3xl opacity-0"></div>
    <div class="bg-glow absolute top-1/2 left-1/4 -translate-y-1/2 w-72 h-72 md:w-80 md:h-80 bg-tertiary-500/30 rounded-full mix-blend-lighten filter blur-3xl opacity-0"></div>
    <div class="bg-glow absolute top-1/2 right-1/4 -translate-y-1/2 w-64 h-64 md:w-72 md:h-72 bg-secondary-600/30 rounded-full mix-blend-lighten filter blur-3xl opacity-0"></div>

    <!-- Central Chart -->
    <div id="chart-container-component" class="relative w-60 h-60 md:w-72 md:h-72 bg-zinc-800/40 p-5 md:p-6 rounded-3xl shadow-2xl flex items-end justify-around gap-3 md:gap-4 border border-zinc-700/50 pointer-events-auto">
        <div data-content-key="animated-chart.title" class="absolute top-3 md:top-4 left-4 md:left-6 text-zinc-200 text-sm md:text-base font-semibold">Votre Croissance</div>
        <!-- Chart bar columns -->
        <div class="w-1/4 h-full flex items-end">
            <div class="chart-bar w-full h-2/5 bg-gradient-to-t from-primary-500 to-primary-300 rounded-t-lg"></div>
        </div>
        <div class="w-1/4 h-full flex items-end">
            <div class="chart-bar w-full h-3/5 bg-gradient-to-t from-secondary-500 to-secondary-300 rounded-t-lg"></div>
        </div>
        <div class="w-1/4 h-full flex items-end">
            <div class="chart-bar w-full h-4/5 bg-gradient-to-t from-tertiary-500 to-tertiary-300 rounded-t-lg"></div>
        </div>
        <div class="w-1/4 h-full flex items-end">
            <div class="chart-bar w-full h-full bg-gradient-to-t from-primary-700 to-primary-500 rounded-t-lg"></div>
        </div>
    </div>

    <!-- Floating Metric Cards -->
    <!-- ROI -->
    <div class="metric-card absolute top-8 md:top-12 left-2 sm:left-8 md:left-12 opacity-0 pointer-events-auto" data-float-duration="5">
        <div class="glass-effect p-3 rounded-xl shadow-lg w-36 md:w-40">
            <div class="flex items-center gap-2">
                <div class="bg-primary-500/20 p-1.5 rounded-md">
                    <flux:icon.currency-dollar class="w-5 h-5 text-primary-300" />
                </div>
                <div>
                    <p data-content-key="animated-chart.roi.label" class="text-xs text-zinc-300">ROI</p>
                    <p class="text-lg font-bold text-white"><span class="counter" data-target="350">0</span><span data-content-key="animated-chart.roi.unit">%</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversions -->
    <div class="metric-card absolute bottom-4 md:bottom-8 right-0 sm:right-8 md:right-12 opacity-0 pointer-events-auto" data-float-duration="7">
        <div class="glass-effect p-3 rounded-xl shadow-lg w-40 md:w-44">
            <div class="flex items-center gap-2">
                <div class="bg-secondary-500/20 p-1.5 rounded-md">
                   <flux:icon.arrow-trending-up class="w-5 h-5 text-secondary-300" />
                </div>
                <div>
                    <p data-content-key="animated-chart.conversions.label" class="text-xs text-zinc-300">Conversions</p>
                    <p class="text-lg font-bold text-white"><span data-content-key="animated-chart.conversions.prefix">+</span><span class="counter" data-target="1200">0</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Impressions -->
    <div class="metric-card absolute bottom-16 md:bottom-20 left-0 sm:left-4 md:left-8 opacity-0 pointer-events-auto" data-float-duration="6.5">
        <div class="glass-effect p-3 rounded-xl shadow-lg w-44 md:w-48">
            <div class="flex items-center gap-2">
                 <div class="bg-tertiary-500/20 p-1.5 rounded-md">
                    <flux:icon.eye class="w-5 h-5 text-tertiary-300" />
                </div>
                <div>
                    <p data-content-key="animated-chart.impressions.label" class="text-xs text-zinc-300">Impressions</p>
                    <p class="text-lg font-bold text-white"><span class="counter" data-target="2.3">0</span><span data-content-key="animated-chart.impressions.unit">M+</span></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Click-Through Rate -->
    <div class="metric-card absolute top-16 md:top-20 right-0 sm:right-4 md:right-8 opacity-0 pointer-events-auto" data-float-duration="5.5">
         <div class="glass-effect p-3 rounded-xl shadow-lg w-44 md:w-48">
            <div class="flex items-center gap-2">
                 <div class="bg-primary-500/10 p-1.5 rounded-md">
                    <flux:icon.cursor-arrow-rays class="w-5 h-5 text-primary-200" />
                </div>
                <div>
                    <p data-content-key="animated-chart.ctr.label" class="text-xs text-zinc-300">Taux de Clics</p>
                    <p class="text-lg font-bold text-white"><span class="counter" data-target="15.8">0</span><span data-content-key="animated-chart.ctr.unit">%</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Basic styles, animations will be handled by GSAP */
    .chart-bar {
        transform-origin: bottom;
        transform: scaleY(0); /* Initial state for GSAP */
    }

    .metric-card {
        /* opacity: 0; Set by GSAP */
        /* transform: translateY(8px); Set by GSAP */
    }

    .bg-glow {
        /* opacity: 0; Set by GSAP */
        /* transform: scale(1); Set by GSAP */
    }

    /* Glassmorphism effect consistent with dark theme */
    .glass-effect {
        background: rgba(var(--color-zinc-800-rgb, 39 39 42) / 0.3);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(var(--color-zinc-700-rgb, 55 65 81) / 0.5);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gsap === 'undefined') {
            console.warn('GSAP is not defined. Animations for animated-chart component will not run.');
            return;
        }

        const componentRoot = document.getElementById('chart-container-component')?.closest('.relative.w-full');
        if (!componentRoot) return;

        const glows = componentRoot.querySelectorAll('.bg-glow');
        const chartBars = componentRoot.querySelectorAll('.chart-bar');
        const metricCards = componentRoot.querySelectorAll('.metric-card');
        const counters = componentRoot.querySelectorAll('.counter');

        // Set initial states for GSAP animations
        gsap.set(glows, { opacity: 0, scale: 1 });
        gsap.set(chartBars, { scaleY: 0, transformOrigin: 'bottom' });
        gsap.set(metricCards, { opacity: 0, y: 8 });

        const observer = new IntersectionObserver((entries, observerInstance) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const masterTimeline = gsap.timeline();

                    // 1. Background Glows Animation
                    masterTimeline.to(glows, {
                        opacity: 0.6, 
                        scale: 1.05,
                        duration: 4,
                        repeat: -1,
                        yoyo: true,
                        ease: "sine.inOut",
                        stagger: {
                            each: 1,
                            from: "random"
                        }
                    }, 0);

                    // 2. Chart Bars Animation
                    masterTimeline.to(chartBars, {
                        scaleY: 1,
                        duration: 1.5,
                        ease: "back.out(1.5)", // Overshoot effect
                        stagger: 0.2
                    }, 0.2); // Start slightly after glows begin

                    // 3. Metric Cards - Fade In and Initial Position
                    metricCards.forEach((card, index) => {
                        const cardEntranceDelay = 0.5 + index * 0.25; // Staggered entrance
                        masterTimeline.to(card, {
                            opacity: 1,
                            y: 0,
                            duration: 1,
                            ease: "power2.out"
                        }, cardEntranceDelay);

                        // 4. Metric Cards - Float Animation (starts after fade in)
                        // Apply this as a separate tween so it can loop independently
                        gsap.to(card, {
                            y: "-=12", // Relative float
                            duration: parseFloat(card.dataset.floatDuration) || 6, // Use data-attribute or default
                            repeat: -1,
                            yoyo: true,
                            ease: "sine.inOut",
                            delay: cardEntranceDelay + 0.8 // Start floating after entrance animation slightly completes
                        });

                        // 5. Number Counters
                        const counterEl = card.querySelector('.counter');
                        if (counterEl) {
                            const target = +counterEl.getAttribute('data-target');
                            const isFloat = counterEl.getAttribute('data-target').includes('.');
                            let countObj = { val: 0 };

                            masterTimeline.to(countObj, {
                                val: target,
                                duration: 1.5,
                                ease: "power1.out",
                                onUpdate: () => {
                                    if (isFloat) {
                                        counterEl.innerText = countObj.val.toFixed(1);
                                    } else {
                                        counterEl.innerText = Math.ceil(countObj.val).toLocaleString('fr-FR');
                                    }
                                }
                            }, cardEntranceDelay + 0.3); // Start counter during card fade in
                        }
                    });
                    
                    observerInstance.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        const chartContainerForObserver = componentRoot.querySelector('#chart-container-component');
        if (chartContainerForObserver) {
            observer.observe(chartContainerForObserver);
        } else {
            // Fallback to run animations if observer target not found (should not happen with current IDs)
            console.warn("Animated chart observer target not found, running animations directly.");
            // Manually trigger a mock intersection if needed for debugging or specific scenarios
            // This part would need a direct call to the animation logic if observer fails entirely.
        }
    });
</script> 