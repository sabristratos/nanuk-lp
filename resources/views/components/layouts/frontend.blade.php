<x-layouts.base :title="$title ?? null">
    <div class="dark min-h-screen bg-zinc-950 relative isolate">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu blur-3xl sm:-top-80" aria-hidden="true">
            <div
                id="background-gradient"
                class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-zinc-800 to-zinc-900 opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <!-- Header -->
        <header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 ease-in-out" style="transform: translateY(-100%);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20">
                    <div class="flex-shrink-0">
                        @if(setting('show_logo_in_header', true))
                            <a href="/" class="text-xl font-bold text-primary-500 hover:text-primary-400 transition-colors duration-150 ease-in-out">
                                {{ setting('site_name', config('app.name', 'Laravel')) }}
                            </a>
                        @endif
                    </div>

                    <nav class="hidden md:flex items-center space-x-8 ml-auto">
                        <a href="#hero" class="text-base font-medium text-white hover:text-secondary-600 transition-colors duration-150 ease-in-out">Accueil</a>
                        <a href="#methode" class="text-base font-medium text-white hover:text-secondary-600 transition-colors duration-150 ease-in-out">Notre Méthode</a>
                        <a href="#appel" class="text-base font-medium text-white hover:text-secondary-600 transition-colors duration-150 ease-in-out">L'Appel</a>
                        <a href="#pour-qui" class="text-base font-medium text-white hover:text-secondary-600 transition-colors duration-150 ease-in-out">Pour Qui?</a>
                        <a href="#pourquoi-nous" class="text-base font-medium text-white hover:text-secondary-600 transition-colors duration-150 ease-in-out">Pourquoi Nous?</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="w-full">
            {{ $slot }}
        </main>

        <!-- Footer Gradient -->
        <div class="absolute inset-x-0 bottom-0 -z-10 transform-gpu overflow-hidden blur-3xl" aria-hidden="true">
            <div
                id="background-gradient-footer"
                class="relative left-[calc(50%+15rem)] aspect-[1155/678] w-[46.125rem] -translate-x-1/2 rotate-[150deg] bg-gradient-to-tr from-primary-500 to-secondary-600 opacity-20 sm:left-[calc(50%+25rem)] sm:w-[82.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-zinc-900 border-t border-zinc-700/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <div class="text-sm text-zinc-400">
                        {!! Str::of(setting('footer_copyright_text', '&copy; {year} {site_name}. All rights reserved.'))->replace('{year}', date('Y'))->replace('{site_name}', setting('site_name', config('app.name', 'Laravel'))) !!}
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="#" class="text-sm text-zinc-400 hover:text-primary-400 transition-colors duration-150 ease-in-out">Privacy Policy</a>
                        <a href="#" class="text-sm text-zinc-400 hover:text-primary-400 transition-colors duration-150 ease-in-out">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.getElementById('main-header');
            const mainContent = document.querySelector('main');
            const backgroundGradient = document.getElementById('background-gradient');
            const backgroundGradientFooter = document.getElementById('background-gradient-footer');

            if (backgroundGradient && typeof gsap !== 'undefined') {
                gsap.to(backgroundGradient, {
                    rotation: '+=90',
                    scale: 1.2,
                    duration: 40,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });
            }

            if (backgroundGradientFooter && typeof gsap !== 'undefined') {
                gsap.to(backgroundGradientFooter, {
                    rotation: '-=70',
                    scale: 1.25,
                    duration: 50,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });
            }

            if (header && typeof gsap !== 'undefined') {
                // Animate header sliding down
                gsap.to(header, {
                    y: 0, // Target translateY(0)
                    duration: 0.8,
                    ease: 'power2.out',
                    delay: 0.2
                });

                // Function to set padding on main content
                function adjustMainContentPadding() {
                    if (mainContent) {
                        const headerHeight = header.offsetHeight;
                        mainContent.style.paddingTop = `${headerHeight}px`;
                    }
                }
                
                adjustMainContentPadding();
                window.addEventListener('resize', adjustMainContentPadding);

                // Smooth scroll for anchor links in the header
                document.querySelectorAll('#main-header a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        try {
                            const targetElement = document.querySelector(targetId);
                            if (targetElement) {
                                const headerOffset = header.offsetHeight;
                                const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                                const offsetPosition = elementPosition - headerOffset;

                                window.scrollTo({
                                    top: offsetPosition,
                                    behavior: 'smooth'
                                });
                            }
                        } catch (error) {
                            console.warn(`Smooth scroll target ${targetId} not found or invalid.`, error);
                        }
                    });
                });

                // Handle header background on scroll
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 50) { // Adjust 50 to your preferred scroll distance
                        header.classList.add('header-scrolled');
                    } else {
                        header.classList.remove('header-scrolled');
                    }
                });

            } else {
                if (!header) console.warn('Main header (ID: main-header) not found for animation.');
                if (typeof gsap === 'undefined') console.warn('GSAP is not defined. Header animation and smooth scroll may not work.');
                // Fallback for main content padding if GSAP/header isn't there for some reason
                if(header && mainContent) mainContent.style.paddingTop = `${header.offsetHeight}px`; 
            }
        });
    </script>
</x-layouts.base> 