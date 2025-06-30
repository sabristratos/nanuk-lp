<x-layouts.base :title="$title ?? null">
    <div class="dark min-h-screen relative isolate bg-zinc-950 text-white">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu blur-3xl sm:-top-80" aria-hidden="true">
            <div
                id="background-gradient"
                class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-zinc-900 to-zinc-950 opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <!-- Header -->
        <header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 ease-in-out bg-zinc-950/80 backdrop-blur-sm border-b border-zinc-900" style="transform: translateY(-100%);" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20">
                    <div class="flex-shrink-0">
                        @if(setting('show_logo_in_header', true))
                            <a href="/" class="flex items-center">
                                <img src="{{ \App\Facades\Settings::getLogoUrl() }}" alt="{{ setting('site_name', config('app.name', 'Laravel')) }}" class="h-8 md:h-10 w-auto">
                            </a>
                        @else
                            <a href="/" class="text-xl font-bold text-primary-400 hover:text-primary-300 transition-colors duration-150 ease-in-out">
                                {{ setting('site_name', config('app.name', 'Laravel')) }}
                            </a>
                        @endif
                    </div>

                    <nav class="hidden md:flex items-center space-x-8 ml-auto">
                        <a href="#hero" class="text-base font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out">Accueil</a>
                        <a href="#methode" class="text-base font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out">Notre Méthode</a>
                        <a href="#appel" class="text-base font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out">L'Appel</a>
                        <a href="#pour-qui" class="text-base font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out">Pour Qui?</a>
                        <a href="#pourquoi-nous" class="text-base font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out">Pourquoi Nous?</a>
                        <x-cta-button
                            click="scrollToForm()"
                            class="px-6 py-2 text-sm"
                            data-element-key="navigation.cta"
                            data-content-key="navigation.cta"
                        >
                        Réserver votre appel 
                        </x-cta-button>
                    </nav>

                    <!-- Mobile CTA and menu button container -->
                    <div class="md:hidden flex items-center space-x-3">
                        <!-- Mobile CTA Button -->
                        <button 
                            @click="scrollToForm()"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-full font-semibold text-black bg-primary-400 hover:bg-white focus:outline-none focus:ring-2 focus:ring-primary-300 transition-all duration-300 ease-in-out text-sm"
                        >
                            Réserver
                        </button>

                        <!-- Mobile menu button -->
                        <button 
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-zinc-300 hover:text-primary-400 hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-400 transition-colors duration-150 ease-in-out"
                            aria-expanded="false"
                            aria-controls="mobile-menu"
                        >
                            <span class="sr-only">Ouvrir le menu principal</span>
                            <!-- Icon when menu is closed -->
                            <svg 
                                x-show="!mobileMenuOpen"
                                class="block h-6 w-6" 
                                xmlns="http://www.w3.org/2000/svg" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Icon when menu is open -->
                            <svg 
                                x-show="mobileMenuOpen"
                                class="block h-6 w-6" 
                                xmlns="http://www.w3.org/2000/svg" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div 
                x-show="mobileMenuOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-full"
                class="md:hidden fixed inset-y-0 right-0 z-50 w-full max-w-sm bg-black border-l border-zinc-800 shadow-2xl"
                style="background-color: #000000 !important;"
                id="mobile-menu"
                @click.away="mobileMenuOpen = false"
            >
                <div class="flex flex-col h-screen bg-black" style="background-color: #000000 !important;">
                    <!-- Mobile menu header -->
                    <div class="flex items-center justify-between p-6 border-b border-zinc-800 bg-black" style="background-color: #000000 !important;">
                        <h2 class="text-lg font-semibold text-white">Menu</h2>
                        <button 
                            @click="mobileMenuOpen = false"
                            class="text-zinc-400 hover:text-white transition-colors duration-150 ease-in-out"
                        >
                            <span class="sr-only">Fermer le menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile menu navigation -->
                    <nav class="flex-1 px-6 py-4 space-y-4 bg-black" style="background-color: #000000 !important;">
                        <a 
                            href="#hero" 
                            @click="mobileMenuOpen = false"
                            class="block text-lg font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out py-3 border-b border-zinc-800/50"
                        >
                            Accueil
                        </a>
                        <a 
                            href="#methode" 
                            @click="mobileMenuOpen = false"
                            class="block text-lg font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out py-3 border-b border-zinc-800/50"
                        >
                            Notre Méthode
                        </a>
                        <a 
                            href="#appel" 
                            @click="mobileMenuOpen = false"
                            class="block text-lg font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out py-3 border-b border-zinc-800/50"
                        >
                            L'Appel
                        </a>
                        <a 
                            href="#pour-qui" 
                            @click="mobileMenuOpen = false"
                            class="block text-lg font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out py-3 border-b border-zinc-800/50"
                        >
                            Pour Qui?
                        </a>
                        <a 
                            href="#pourquoi-nous" 
                            @click="mobileMenuOpen = false"
                            class="block text-lg font-medium text-zinc-300 hover:text-primary-400 transition-colors duration-150 ease-in-out py-3 border-b border-zinc-800/50"
                        >
                            Pourquoi Nous?
                        </a>
                    </nav>

                    <!-- Mobile menu CTA section -->
                    <div class="px-6 py-4 bg-black" style="background-color: #000000 !important;">
                        <x-cta-button
                            click="scrollToForm(); mobileMenuOpen = false"
                            class="w-full justify-center py-4 text-base"
                            data-element-key="mobile-menu.cta"
                            data-content-key="mobile-menu.cta"
                        >
                            Réserver votre appel gratuit
                        </x-cta-button>
                    </div>

                    <!-- Mobile menu footer -->
                    <div class="p-6 border-t border-zinc-800 bg-black" style="background-color: #000000 !important;">
                        <div class="text-sm text-zinc-400">
                            © Nanuk Web inc.
                        </div>
                    </div>
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
        <footer class="border-t border-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <div class="text-sm text-zinc-400">
                        © Nanuk Web inc.
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="https://nanukweb.ca/politique-de-confidentialite/" target="_blank" rel="noopener noreferrer" class="text-sm text-zinc-400 hover:text-primary-400 transition-colors duration-150 ease-in-out">Politique de Confidentialité</a>
                        <a href="https://nanukweb.ca/conditions-utilisation/" target="_blank" rel="noopener noreferrer" class="text-sm text-zinc-400 hover:text-primary-400 transition-colors duration-150 ease-in-out">Conditions d'Utilisation</a>
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

                // Function to scroll to form section or open modal
                window.scrollToForm = function() {
                    const formSection = document.getElementById('form-section');
                    if (formSection) {
                        const headerOffset = header.offsetHeight;
                        const elementPosition = formSection.getBoundingClientRect().top + window.scrollY;
                        const offsetPosition = elementPosition - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    } else {
                        // If no embedded form, trigger modal
                        if (window.Livewire) {
                            window.Livewire.dispatch('openModal');
                        }
                    }
                };

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
