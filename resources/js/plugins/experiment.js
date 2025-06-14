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
                    return;
                }

                // Search for elements with various data attributes that match the target
                const selectors = [
                    `[data-key="${mod.target}"]`,
                    `[data-content-key="${mod.target}"]`,
                    `[data-element-key="${mod.target}"]`
                ];
                
                let elements = [];
                
                // First, search within the component scope
                selectors.forEach(selector => {
                    const found = this.$el.querySelectorAll(selector);
                    elements.push(...found);
                });

                // If no elements found within component, search the entire document
                if (!elements.length) {
                    selectors.forEach(selector => {
                        const found = document.querySelectorAll(selector);
                        elements.push(...found);
                    });
                }

                if (!elements.length) {
                    console.warn(`Experiment target element not found with any of: ${selectors.join(', ')}`);
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
                    // Handle both boolean and string values
                    const isVisible = mod.payload.visible === true || mod.payload.visible === '1' || mod.payload.visible === 1;
                    
                    console.log('Visibility modification:', { target: mod.target, isVisible, element });
                    
                    if (isVisible) {
                        element.style.display = '';
                        element.style.removeProperty('display');
                    } else {
                        // Multiple approaches to ensure the element is hidden
                        element.style.setProperty('display', 'none', 'important');
                        element.style.setProperty('visibility', 'hidden', 'important');
                        element.style.setProperty('opacity', '0', 'important');
                        element.setAttribute('hidden', 'true');
                        
                        // Handle Alpine.js x-show conflicts
                        if (element._x_dataStack && element._x_dataStack.length > 0) {
                            const alpineData = element._x_dataStack[0];
                            console.log('Alpine data found:', alpineData);
                            if ('bannerVisible' in alpineData) {
                                alpineData.bannerVisible = false;
                                console.log('Set bannerVisible to false');
                            }
                            if ('visible' in alpineData) alpineData.visible = false;
                            if ('show' in alpineData) alpineData.show = false;
                        }
                        
                        // Also try to find and modify Alpine data via Alpine.js API
                        if (window.Alpine && element._x_dataStack) {
                            try {
                                const data = window.Alpine.$data(element);
                                if (data && 'bannerVisible' in data) {
                                    data.bannerVisible = false;
                                    console.log('Set Alpine bannerVisible via API');
                                }
                            } catch (e) {
                                console.log('Alpine API access failed:', e);
                            }
                        }
                        
                        // Force a delay to override Alpine initialization
                        setTimeout(() => {
                            element.style.setProperty('display', 'none', 'important');
                            console.log('Applied delayed visibility override');
                        }, 100);
                    }
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