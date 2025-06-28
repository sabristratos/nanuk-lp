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
                    <form wire:submit.prevent="submit"
                          x-data="{
                            fields: ['firstName','lastName','email','phone','website','businessYears','mainObjective','onlineAdvertisingExperience','monthlyBudget','readyToInvest'],
                            save() {
                              this.fields.forEach(f => localStorage.setItem('lpform_' + f, $refs[f]?.value ?? ''));
                            },
                            load() {
                              this.fields.forEach(f => { if ($refs[f] && localStorage.getItem('lpform_' + f)) $refs[f].value = localStorage.getItem('lpform_' + f); });
                            }
                          }"
                          x-init="load()"
                          @change.debounce.500="save()"
                          autocomplete="on"
                    >
                        <div class="space-y-6 mt-4">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.first-name.label">Prénom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="firstName" id="firstName" name="first_name" x-ref="firstName" placeholder="Votre prénom"
                                           data-content-key="form-modal.first-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="given-name">
                                    @error('firstName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.last-name.label">Nom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="lastName" id="lastName" name="last_name" x-ref="lastName" placeholder="Votre nom"
                                           data-content-key="form-modal.last-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="family-name">
                                    @error('lastName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.email.label">Courriel<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="email" wire:model.blur="email" id="email" name="email" x-ref="email" placeholder="vous@exemple.com"
                                       data-content-key="form-modal.email.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="email">
                                @error('email') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.phone.label">Téléphone<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="tel" wire:model.blur="phone" id="phone" name="phone" x-ref="phone" placeholder="+1 (555) 123-4567"
                                       data-content-key="form-modal.phone.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="tel">
                                @error('phone') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.website.label">Site web<span class="text-zinc-400 text-xs ml-1">(facultatif)</span></label>
                                <input type="url" wire:model.blur="website" id="website" name="website" x-ref="website" placeholder="https://www.votresite.com"
                                       data-content-key="form-modal.website.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="url">
                                @error('website') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="businessYears" class="block text-sm font-medium text-zinc-300 mb-1">1. Depuis combien d'années êtes-vous en affaires ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="businessYears" id="businessYears" name="business_years" x-ref="businessYears"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez une option...</option>
                                        <option value="Je débute">Je débute</option>
                                        <option value="1 à 3 ans">1 à 3 ans</option>
                                        <option value="De 3 à 5 ans">De 3 à 5 ans</option>
                                        <option value="Plus de 5 ans">Plus de 5 ans</option>
                                    </select>
                                </div>
                                @error('businessYears') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="mainObjective" class="block text-sm font-medium text-zinc-300 mb-1">2. Quel est votre objectif principal ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="mainObjective" id="mainObjective" name="main_objective" x-ref="mainObjective"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez un objectif...</option>
                                        <option value="Générer des leads qualifiés">Générer des leads qualifiés</option>
                                        <option value="Promouvoir un service professionnel">Promouvoir un service professionnel</option>
                                        <option value="Augmenter la notoriété de votre marque">Augmenter la notoriété de votre marque</option>
                                        <option value="Recruter des candidats">Recruter des candidats</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                @error('mainObjective') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="onlineAdvertisingExperience" class="block text-sm font-medium text-zinc-300 mb-1">3. Avez-vous déjà fait de la publicité en ligne, vous-même ou avec une agence marketing ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="onlineAdvertisingExperience" id="onlineAdvertisingExperience" name="online_advertising_experience" x-ref="onlineAdvertisingExperience"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez une option...</option>
                                        <option value="Oui">Oui</option>
                                        <option value="Non">Non</option>
                                    </select>
                                </div>
                                @error('onlineAdvertisingExperience') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="monthlyBudget" class="block text-sm font-medium text-zinc-300 mb-1">4. Quel est votre budget mensuel pour la publicité ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="monthlyBudget" id="monthlyBudget" name="monthly_budget" x-ref="monthlyBudget"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez un budget...</option>
                                        <option value="Entre 1 250 et 2 000 $">Entre 1 250 et 2 000 $</option>
                                        <option value="Entre 2 000 et 2 750 $">Entre 2 000 et 2 750 $</option>
                                        <option value="Plus de 2 750 $">Plus de 2 750 $</option>
                                    </select>
                                </div>
                                @error('monthlyBudget') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="readyToInvest" class="block text-sm font-medium text-zinc-300 mb-1">5. Êtes-vous prêt à investir dans des services de marketing numérique pour atteindre vos objectifs ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="readyToInvest" id="readyToInvest" name="ready_to_invest" x-ref="readyToInvest"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez une option...</option>
                                        <option value="Oui, je veux passer à l'action !">Oui, je veux passer à l'action !</option>
                                        <option value="Non, j'étais juste curieux.">Non, j'étais juste curieux.</option>
                                    </select>
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

                        <div class="mt-8 pt-5 border-t border-zinc-700 flex justify-center">
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
                    <form wire:submit.prevent="submit" class="mt-6"
                          x-data="{
                            fields: ['firstName','lastName','email','phone','website','businessYears','mainObjective','onlineAdvertisingExperience','monthlyBudget','readyToInvest'],
                            save() {
                              this.fields.forEach(f => localStorage.setItem('lpform_' + f, $refs[f]?.value ?? ''));
                            },
                            load() {
                              this.fields.forEach(f => { if ($refs[f] && localStorage.getItem('lpform_' + f)) $refs[f].value = localStorage.getItem('lpform_' + f); });
                            }
                          }"
                          x-init="load()"
                          @change.debounce.500="save()"
                          autocomplete="on"
                    >
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.first-name.label">Prénom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="firstName" id="firstName" name="first_name" x-ref="firstName" placeholder="Votre prénom"
                                           data-content-key="form-modal.first-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="given-name">
                                    @error('firstName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.last-name.label">Nom<span class="text-red-400 ml-0.5">*</span></label>
                                    <input type="text" wire:model.blur="lastName" id="lastName" name="last_name" x-ref="lastName" placeholder="Votre nom"
                                           data-content-key="form-modal.last-name.placeholder"
                                           class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="family-name">
                                    @error('lastName') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.email.label">Courriel<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="email" wire:model.blur="email" id="email" name="email" x-ref="email" placeholder="vous@exemple.com"
                                       data-content-key="form-modal.email.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="email">
                                @error('email') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.phone.label">Téléphone<span class="text-red-400 ml-0.5">*</span></label>
                                <input type="tel" wire:model.blur="phone" id="phone" name="phone" x-ref="phone" placeholder="+1 (555) 123-4567"
                                       data-content-key="form-modal.phone.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="tel">
                                @error('phone') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-zinc-300 mb-1" data-content-key="form-modal.website.label">Site web<span class="text-zinc-400 text-xs ml-1">(facultatif)</span></label>
                                <input type="url" wire:model.blur="website" id="website" name="website" x-ref="website" placeholder="https://www.votresite.com"
                                       data-content-key="form-modal.website.placeholder"
                                       class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60" autocomplete="url">
                                @error('website') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="businessYears" class="block text-sm font-medium text-zinc-300 mb-1">1. Depuis combien d'années êtes-vous en affaires ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="businessYears" id="businessYears" name="business_years" x-ref="businessYears"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez une option...</option>
                                        <option value="Je débute">Je débute</option>
                                        <option value="1 à 3 ans">1 à 3 ans</option>
                                        <option value="De 3 à 5 ans">De 3 à 5 ans</option>
                                        <option value="Plus de 5 ans">Plus de 5 ans</option>
                                    </select>
                                </div>
                                @error('businessYears') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="mainObjective" class="block text-sm font-medium text-zinc-300 mb-1">2. Quel est votre objectif principal ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="mainObjective" id="mainObjective" name="main_objective" x-ref="mainObjective"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez un objectif...</option>
                                        <option value="Générer des leads qualifiés">Générer des leads qualifiés</option>
                                        <option value="Promouvoir un service professionnel">Promouvoir un service professionnel</option>
                                        <option value="Augmenter la notoriété de votre marque">Augmenter la notoriété de votre marque</option>
                                        <option value="Recruter des candidats">Recruter des candidats</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                @error('mainObjective') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="onlineAdvertisingExperience" class="block text-sm font-medium text-zinc-300 mb-1">3. Avez-vous déjà fait de la publicité en ligne, vous-même ou avec une agence marketing ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="onlineAdvertisingExperience" id="onlineAdvertisingExperience" name="online_advertising_experience" x-ref="onlineAdvertisingExperience"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez une option...</option>
                                        <option value="Oui">Oui</option>
                                        <option value="Non">Non</option>
                                    </select>
                                </div>
                                @error('onlineAdvertisingExperience') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="monthlyBudget" class="block text-sm font-medium text-zinc-300 mb-1">4. Quel est votre budget mensuel pour la publicité ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="monthlyBudget" id="monthlyBudget" name="monthly_budget" x-ref="monthlyBudget"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez un budget...</option>
                                        <option value="Entre 1 250 et 2 000 $">Entre 1 250 et 2 000 $</option>
                                        <option value="Entre 2 000 et 2 750 $">Entre 2 000 et 2 750 $</option>
                                        <option value="Plus de 2 750 $">Plus de 2 750 $</option>
                                    </select>
                                </div>
                                @error('monthlyBudget') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="readyToInvest" class="block text-sm font-medium text-zinc-300 mb-1">5. Êtes-vous prêt à investir dans des services de marketing numérique pour atteindre vos objectifs ?<span class="text-red-400 ml-0.5">*</span></label>
                                <div class="relative">
                                    <select wire:model.blur="readyToInvest" id="readyToInvest" name="ready_to_invest" x-ref="readyToInvest"
                                            class="block w-full rounded-full border-2 border-white/50 bg-tertiary-900 py-2.5 px-4 text-zinc-100 text-base shadow-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none transition-colors duration-150 ease-in-out placeholder:text-zinc-500 appearance-none pr-10 hover:border-primary-400 hover:ring-1 hover:ring-primary-400 hover:ring-opacity-60">
                                        <option value="">Sélectionnez une option...</option>
                                        <option value="Oui, je veux passer à l'action !">Oui, je veux passer à l'action !</option>
                                        <option value="Non, j'étais juste curieux.">Non, j'étais juste curieux.</option>
                                    </select>
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
