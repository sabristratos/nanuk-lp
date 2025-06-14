<div>
    @if ($displayMode === 'modal')
        <flux:modal name="landing-form" class="max-w-2xl !bg-zinc-950">
            <div class="space-y-6">
                <div class="flex justify-between items-center pb-3">
                    <h3 data-content-key="form-modal.title" class="text-2xl font-semibold text-zinc-100" id="modal-title">
                        Réservez votre consultation gratuite
                    </h3>
                    <flux:modal.close />
                </div>

                @if ($submissionSuccess)
                    <flux:callout variant="success" icon="check-circle">
                        <flux:callout.heading data-content-key="form-modal.success.title">Merci !</flux:callout.heading>
                        <flux:callout.text data-content-key="form-modal.success.message">
                            Votre demande a été soumise avec succès. Nous vous contacterons bientôt.
                        </flux:callout.text>
                        <x-slot name="actions">
                            <flux:button variant="ghost" wire:click="closeModal" data-element-key="form-modal.success.close-button">
                                <span data-content-key="form-modal.success.close-button-text">Fermer</span>
                            </flux:button>
                        </x-slot>
                    </flux:callout>
                @elseif ($submissionFailed)
                    <flux:callout variant="danger" icon="x-circle">
                        <flux:callout.heading data-content-key="form-modal.failure.title">Erreur</flux:callout.heading>
                        <flux:callout.text data-content-key="form-modal.failure.message">
                            {{ $failureMessage }}
                        </flux:callout.text>
                        <x-slot name="actions">
                            <flux:button variant="ghost" wire:click="closeModal" data-element-key="form-modal.failure.close-button">
                                <span data-content-key="form-modal.failure.close-button-text">Fermer</span>
                            </flux:button>
                        </x-slot>
                    </flux:callout>
                @else
                    <form wire:submit.prevent="submit">
                        <div class="space-y-6 mt-4">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.first-name.label">Prénom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="firstName" id="firstName" placeholder="Votre prénom"
                                           data-content-key="form-modal.first-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                    @error('firstName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.last-name.label">Nom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="lastName" id="lastName" placeholder="Votre nom"
                                           data-content-key="form-modal.last-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                    @error('lastName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.email.label">Courriel<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="email" wire:model.blur="email" id="email" placeholder="vous@exemple.com"
                                       data-content-key="form-modal.email.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                @error('email') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.phone.label">Téléphone<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="tel" wire:model.blur="phone" id="phone" placeholder="+1 (555) 123-4567"
                                       data-content-key="form-modal.phone.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                @error('phone') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.website.label">Site web<span class="text-zinc-400 text-xs ml-1">(facultatif)</span></label>
                                <input type="url" wire:model.blur="website" id="website" placeholder="https://www.votresite.com"
                                       data-content-key="form-modal.website.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                @error('website') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="primaryGoal" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.goal.label">Quel est votre objectif principal?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="primaryGoal" id="primaryGoal"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option data-content-key="form-modal.goal.option-placeholder" value="">Sélectionnez un objectif...</option>
                                        <option data-content-key="form-modal.goal.option-sales" value="Augmenter mes ventes en ligne">Augmenter mes ventes en ligne</option>
                                        <option data-content-key="form-modal.goal.option-leads" value="Générer plus de leads qualifiés">Générer plus de leads qualifiés</option>
                                        <option data-content-key="form-modal.goal.option-visibility" value="Améliorer la visibilité de ma marque">Améliorer la visibilité de ma marque</option>
                                        <option data-content-key="form-modal.goal.option-other" value="Autre">Autre</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('primaryGoal') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="digitalMarketingExperience" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.experience.label">Depuis combien de temps utilisez-vous des stratégies de marketing numérique?<span class="text-red-400 ml-0.5">*</span></label>
                                 <div class="relative">
                                    <select wire:model.blur="digitalMarketingExperience" id="digitalMarketingExperience"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option data-content-key="form-modal.experience.option-placeholder" value="">Sélectionnez une option...</option>
                                        <option data-content-key="form-modal.experience.option-new" value="Je débute">Je débute</option>
                                        <option data-content-key="form-modal.experience.option-6m-1y" value="6 mois à 1 an">6 mois à 1 an</option>
                                        <option data-content-key="form-modal.experience.option-1y-3y" value="1 à 3 ans">1 à 3 ans</option>
                                        <option data-content-key="form-modal.experience.option-3y-plus" value="Plus de 3 ans">Plus de 3 ans</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('digitalMarketingExperience') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="readyToInvest" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.investment.label">Êtes-vous prêt à investir dans des services de marketing numérique pour atteindre vos objectifs?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="readyToInvest" id="readyToInvest"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option data-content-key="form-modal.investment.option-placeholder" value="">Sélectionnez une option...</option>
                                        <option data-content-key="form-modal.investment.option-yes" value="Oui je suis prêt à passer à l\'action">Oui je suis prêt à passer à l\'action</option>
                                        <option data-content-key="form-modal.investment.option-no" value="Non je ne suis pas intéressé pour l\'instant">Non je ne suis pas intéressé pour l\'instant</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('readyToInvest') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-2">
                                <div class="flex items-start space-x-3">
                                    <div class="relative flex items-center h-5">
                                        <input id="consent" wire:model="consent" type="checkbox"
                                               class="peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-white/50 bg-tertiary-900 checked:bg-primary-400 checked:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-zinc-800 transition-colors duration-150 ease-in-out">
                                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-3.5 w-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <label for="consent" class="text-sm text-zinc-300 cursor-pointer" data-content-key="form-modal.consent.label">
                                        En soumettant ce formulaire, vous consentez à ce que Nanuk Web Inc. communique avec vous par téléphone, message texte (SMS) ou courriel à des fins de suivi, d'information ou de promotion de ses services. Ce consentement est donné de façon libre et éclairée, et vous pouvez le retirer en tout temps.<span class="text-red-400 ml-0.5">*</span>
                                    </label>
                                </div>
                                @error('consent') <span class="block text-sm text-red-400 mt-1 ml-8">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-8 pt-5 border-t border-zinc-700 flex justify-end">
                            <x-cta-button 
                                type="submit" 
                                wire:target="submit" 
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                data-content-key="form-modal.submit-button-text"
                            >
                                <span wire:loading.remove wire:target="submit">RÉSERVER MON APPEL GRATUIT</span>
                                <span wire:loading wire:target="submit">ENVOI EN COURS...</span>
                            </x-cta-button>
                        </div>
                    </form>
                @endif
            </div>
        </flux:modal>
    @else
        {{-- Embedded Form --}}
        <section class="w-full py-16 md:py-24">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="pb-6">
                    <h3 data-content-key="form-modal.title" class="text-2xl md:text-3xl font-semibold text-zinc-100 text-center">
                        Réservez votre consultation gratuite
                    </h3>
                </div>

                @if ($submissionSuccess)
                    <flux:callout variant="success" icon="check-circle">
                        <flux:callout.heading data-content-key="form-modal.success.title">Merci !</flux:callout.heading>
                        <flux:callout.text data-content-key="form-modal.success.message">
                            Votre demande a été soumise avec succès. Nous vous contacterons bientôt.
                        </flux:callout.text>
                    </flux:callout>
                @elseif ($submissionFailed)
                    <flux:callout variant="danger" icon="x-circle">
                        <flux:callout.heading data-content-key="form-modal.failure.title">Erreur</flux:callout.heading>
                        <flux:callout.text data-content-key="form-modal.failure.message">
                            {{ $failureMessage }}
                        </flux:callout.text>
                    </flux:callout>
                @else
                    <form wire:submit.prevent="submit" class="mt-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.first-name.label">Prénom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="firstName" id="firstName" placeholder="Votre prénom"
                                           data-content-key="form-modal.first-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                    @error('firstName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.last-name.label">Nom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="lastName" id="lastName" placeholder="Votre nom"
                                           data-content-key="form-modal.last-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                    @error('lastName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.email.label">Courriel<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="email" wire:model.blur="email" id="email" placeholder="vous@exemple.com"
                                       data-content-key="form-modal.email.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                @error('email') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.phone.label">Téléphone<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="tel" wire:model.blur="phone" id="phone" placeholder="+1 (555) 123-4567"
                                       data-content-key="form-modal.phone.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                @error('phone') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.website.label">Site web<span class="text-zinc-400 text-xs ml-1">(facultatif)</span></label>
                                <input type="url" wire:model.blur="website" id="website" placeholder="https://www.votresite.com"
                                       data-content-key="form-modal.website.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                @error('website') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="primaryGoal" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.goal.label">Quel est votre objectif principal?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="primaryGoal" id="primaryGoal"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option data-content-key="form-modal.goal.option-placeholder" value="">Sélectionnez un objectif...</option>
                                        <option data-content-key="form-modal.goal.option-sales" value="Augmenter mes ventes en ligne">Augmenter mes ventes en ligne</option>
                                        <option data-content-key="form-modal.goal.option-leads" value="Générer plus de leads qualifiés">Générer plus de leads qualifiés</option>
                                        <option data-content-key="form-modal.goal.option-visibility" value="Améliorer la visibilité de ma marque">Améliorer la visibilité de ma marque</option>
                                        <option data-content-key="form-modal.goal.option-other" value="Autre">Autre</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('primaryGoal') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="digitalMarketingExperience" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.experience.label">Depuis combien de temps utilisez-vous des stratégies de marketing numérique?<span class="text-red-400 ml-0.5">*</span></label>
                                 <div class="relative">
                                    <select wire:model.blur="digitalMarketingExperience" id="digitalMarketingExperience"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option data-content-key="form-modal.experience.option-placeholder" value="">Sélectionnez une option...</option>
                                        <option data-content-key="form-modal.experience.option-new" value="Je débute">Je débute</option>
                                        <option data-content-key="form-modal.experience.option-6m-1y" value="6 mois à 1 an">6 mois à 1 an</option>
                                        <option data-content-key="form-modal.experience.option-1y-3y" value="1 à 3 ans">1 à 3 ans</option>
                                        <option data-content-key="form-modal.experience.option-3y-plus" value="Plus de 3 ans">Plus de 3 ans</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('digitalMarketingExperience') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="readyToInvest" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.investment.label">Êtes-vous prêt à investir dans des services de marketing numérique pour atteindre vos objectifs?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="readyToInvest" id="readyToInvest"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option data-content-key="form-modal.investment.option-placeholder" value="">Sélectionnez une option...</option>
                                        <option data-content-key="form-modal.investment.option-yes" value="Oui je suis prêt à passer à l\'action">Oui je suis prêt à passer à l\'action</option>
                                        <option data-content-key="form-modal.investment.option-no" value="Non je ne suis pas intéressé pour l\'instant">Non je ne suis pas intéressé pour l\'instant</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('readyToInvest') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-2">
                                <div class="flex items-start space-x-3">
                                    <div class="relative flex items-center h-5">
                                        <input id="consent" wire:model="consent" type="checkbox"
                                               class="peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-white/50 bg-tertiary-900 checked:bg-primary-400 checked:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-zinc-800 transition-colors duration-150 ease-in-out">
                                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-3.5 w-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <label for="consent" class="text-sm text-zinc-300 cursor-pointer" data-content-key="form-modal.consent.label">
                                        En soumettant ce formulaire, vous consentez à ce que Nanuk Web Inc. communique avec vous par téléphone, message texte (SMS) ou courriel à des fins de suivi, d'information ou de promotion de ses services. Ce consentement est donné de façon libre et éclairée, et vous pouvez le retirer en tout temps.<span class="text-red-400 ml-0.5">*</span>
                                    </label>
                                </div>
                                @error('consent') <span class="block text-sm text-red-400 mt-1 ml-8">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-8 pt-5 text-center">
                            <x-cta-button 
                                type="submit" 
                                wire:target="submit" 
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                data-content-key="form-modal.submit-button-text"
                                class="w-full sm:w-auto"
                            >
                                <span wire:loading.remove wire:target="submit">RÉSERVER MON APPEL GRATUIT</span>
                                <span wire:loading wire:target="submit">ENVOI EN COURS...</span>
                            </x-cta-button>
                        </div>
                    </form>
                @endif
            </div>
        </section>
    @endif
</div>
