import { gsap } from "gsap";

export default function experimentManager(experimentData, elementKey) {
    return {
        experimentData: experimentData,
        variationClasses: '',
        
        init() {
            this.applyModifications();

            this.$watch('experimentData', (newData) => {
                if (newData && newData.variation?.id !== this.experimentData.variation?.id) {
                    // Using location.reload() is a simple way to handle transitions
                    // for complex changes. A more sophisticated GSAP-based transition
                    // could be implemented if needed.
                    location.reload();
                }
            });
        },

        applyModifications() {
            if (!this.experimentData?.modifications) {
                return;
            }

            this.experimentData.modifications.forEach(mod => {
                // If the modification's target matches the component's root key, apply the modification to the root element itself.
                if (mod.target === elementKey) {
                    this.applyModification(this.$el, mod);
                }

                // Then, search for any descendant elements with the same data-key.
                const elements = this.$el.querySelectorAll(`[data-key="${mod.target}"]`);
                if (!elements.length && mod.target !== elementKey) {
                    console.warn(`Experiment target element not found: [data-key="${mod.target}"]`);
                    return;
                }

                elements.forEach(element => {
                    this.applyModification(element, mod);
                });
            });
        },

        applyModification(element, mod) {
            console.log('Applying modification:', { target: mod.target, type: mod.type, payload: mod.payload });

            switch (mod.type) {
                case 'text':
                    // This assumes the payload contains multilang_content and a locale is determined.
                    // A more robust solution would get the current app locale.
                    const locale = document.documentElement.lang || 'en';
                    const content = mod.payload.multilang_content[locale] || mod.payload.multilang_content['en'] || '';
                    if (content) {
                        element.innerHTML = content;
                    }
                    break;
                case 'style':
                    if (mod.payload.property && mod.payload.value) {
                        if (mod.payload.property === 'backgroundColor') {
                            const bgClasses = [...element.classList].filter(c => c.startsWith('bg-') || c.startsWith('from-') || c.startsWith('to-'));
                            element.classList.remove(...bgClasses);
                            element.style.backgroundImage = 'none';
                        }
                        element.style[mod.payload.property] = mod.payload.value;
                    }
                    break;
                case 'visibility':
                    element.style.display = mod.payload.visible ? '' : 'none';
                    break;
                case 'classes':
                    if (mod.payload.classes) {
                        element.classList.add(...mod.payload.classes.split(' ').filter(Boolean));
                    }
                    break;
                case 'layout':
                     if (mod.payload.css_classes) {
                        this.variationClasses = mod.payload.css_classes;
                    }
                    break;
            }
        }
    }
} 