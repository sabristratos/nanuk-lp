import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

// Optional: To make GSAP and ScrollTrigger globally available for easier debugging
// or if you plan to use them directly in inline <script> tags (though component scripts are better).
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

import './bootstrap';
import GLightbox from 'glightbox';

import experimentManager from './plugins/experiment.js';

window.Alpine = Alpine;

Alpine.data('experimentManager', experimentManager);


/**
 * Initializes or re-initializes GLightbox.
 * It targets anchor tags with the class 'glightbox'.
 */
function initializeLightbox() {
    GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: false,
        openEffect: 'zoom',
        closeEffect: 'fade',
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initializeLightbox();
});

document.addEventListener('livewire:navigated', () => {
    initializeLightbox();
});

window.addEventListener('lightbox:refresh', () => {
    initializeLightbox();
});
