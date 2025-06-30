<div>
    <flux:heading size="xl">{{ __('Attachment Management') }}</flux:heading>

    <flux:separator variant="subtle" class="my-8" />

    <div class="space-y-8">
        @can('create-attachments')
        <flux:card>
            <flux:heading size="lg" class="mb-4">{{ __('Upload New File') }}</flux:heading>

            <form wire:submit.prevent="uploadFile"
                  x-data="{ uploading: false, progress: 0, previewUrl: null, isImage: false, hasFile: false }"
                  x-on:livewire-upload-start="uploading = true"
                  x-on:livewire-upload-finish="uploading = false; progress = 0;"
                  x-on:livewire-upload-error="uploading = false; progress = 0;"
                  x-on:livewire-upload-progress="progress = $event.detail.progress">
                
                    <div x-show="previewUrl" class="mb-4">
                        <template x-if="isImage">
                            <a x-bind:href="previewUrl" class="glightbox" data-gallery="upload-preview">
                                <img x-bind:src="previewUrl" class="max-h-48 rounded-lg mx-auto object-contain" alt="{{ __('Preview') }}" />
                            </a>
                        </template>
                        <template x-if="!isImage && previewUrl">
                            <p class="text-sm text-center text-gray-500 dark:text-gray-400">{{ __('Preview not available for this file type.') }}</p>
                        </template>
                    </div>

                    <flux:field>
                        <flux:label for="fileUpload">{{ __('File') }}</flux:label>
                        <flux:input
                            type="file"
                            id="fileUpload"
                            wire:model="file"
                            x-on:change="
                                const file = $event.target.files[0];
                                hasFile = !!file;
                                if (file) {
                                    isImage = file.type.startsWith('image/');
                                    if (isImage) {
                                        previewUrl = URL.createObjectURL(file);
                                    } else {
                                        previewUrl = null;
                                    }
                                }
                            "
                            accept="{{ \App\Facades\Settings::get('attachments_allowed_extensions', '') ? '.' . str_replace(',', ',.', \App\Facades\Settings::get('attachments_allowed_extensions', '')) : '*' }}"
                        />
                        @error('file')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="collectionInput">{{ __('Collection (Optional)') }}</flux:label>
                        <flux:input
                            type="text"
                            id="collectionInput"
                            wire:model="collection"
                            placeholder="{{ __('e.g., avatars, documents, gallery') }}"
                        />
                        @error('collection')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <p>{{ __('Max file size:') }} {{ \Illuminate\Support\Number::fileSize(\App\Facades\Settings::get('attachments_max_upload_size_kb', 10240) * 1024, precision: 2) }}</p>
                            @if(\App\Facades\Settings::get('attachments_allowed_extensions', ''))
                                <p>{{ __('Allowed extensions:') }} {{ \App\Facades\Settings::get('attachments_allowed_extensions', '') }}</p>
                            @endif
                        </div>

                        <flux:button
                            type="submit"
                            icon="arrow-up-tray"
                            x-show="!uploading"
                            :disabled="!hasFile"
                        >
                            {{ __('Upload File') }}
                        </flux:button>

                        <div x-show="uploading" class="flex items-center space-x-2">
                            <div class="w-32 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400" x-text="`${Math.round(progress)}%`"></span>
                        </div>
                    </div>
                </form>
        </flux:card>
        @endcan

        <div class="mb-4 flex justify-between items-center">
            <flux:heading size="lg">{{ __('Uploaded Attachments') }}</flux:heading>
            <div class="flex items-center space-x-2">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Search files...') }}" icon="magnifying-glass" class="max-w-xs" />
                <flux:select wire:model.live="perPage" class="w-auto">
                    <option value="10">10 {{ __('per page') }}</option>
                    <option value="25">25 {{ __('per page') }}</option>
                    <option value="50">50 {{ __('per page') }}</option>
                </flux:select>
            </div>
        </div>

        @if($attachments->isEmpty())
            <x-empty-state
                icon="document-arrow-up"
                heading="{{ __('No attachments found') }}"
                description="{{ $search ? __('Try a different search term.') : __('Upload your first file to get started.') }}"
            />
        @else
            <div class="space-y-3">
                @foreach($attachments as $attachment)
                    <x-attachments.item :attachment="$attachment" :show-actions="false" class="dark:bg-zinc-800 dark:border-zinc-700">
                        <x-slot:actions>
                            <flux:dropdown align="end">
                                <flux:button size="sm" variant="outline" icon:trailing="chevron-down" class="ml-2 dark:text-zinc-300 dark:border-zinc-600 dark:hover:bg-zinc-700">
                                    {{ __('Actions') }}
                                </flux:button>
                                <flux:menu>
                                    @if($attachment->isImage())
                                        <flux:menu.item icon="eye" as="a" href="{{ $attachment->url }}" class="glightbox" data-gallery="attachments-list" data-title="{{ $attachment->filename }}">
                                            {{ __('View Image') }}
                                        </flux:menu.item>
                                    @else
                                        <flux:menu.item icon="eye" as="a" href="{{ $attachment->url }}" target="_blank">
                                            {{ __('View File') }}
                                        </flux:menu.item>
                                    @endif
                                    <flux:menu.item icon="arrow-down-tray" as="a" href="{{ $attachment->url }}" download="{{ $attachment->filename }}">
                                        {{ __('Download') }}
                                    </flux:menu.item>
                                    <flux:menu.item icon="arrow-path" wire:click="showReplaceModal({{ $attachment->id }})">
                                        {{ __('Replace') }}
                                    </flux:menu.item>
                                    <flux:menu.item icon="clipboard-document" x-on:click="
                                            navigator.clipboard.writeText('{{ Storage::disk($attachment->disk)->url($attachment->path) }}');
                                            Flux.toast({ heading: '{{ __('Success') }}', text: '{{ __('File URL copied to clipboard!') }}', variant: 'success' });
                                        ">
                                        {{ __('Copy URL') }}
                                    </flux:menu.item>
                                    <flux:menu.separator />
                                    @can('delete-attachments')
                                    <flux:menu.item
                                        icon="trash"
                                        variant="danger"
                                        wire:click="delete({{ $attachment->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this file?') }} {{ $attachment->filename }}"
                                    >
                                        {{ __('Delete') }}
                                    </flux:menu.item>
                                    @endcan
                                </flux:menu>
                            </flux:dropdown>
                        </x-slot:actions>
                    </x-attachments.item>
                @endforeach
            </div>
            @if ($attachments->hasPages())
                <div class="mt-6">
                    {{ $attachments->links('livewire.flux-pagination') }}
                </div>
            @endif
        @endif
    </div>

    <flux:modal wire:model.defer="showingReplaceModal" class="md:w-96">
        <flux:heading size="lg">
            {{ __('Replace Attachment') }}
            @if($attachmentToReplace)
                <span class="block text-sm font-normal text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Replacing:') }} <strong>{{ $attachmentToReplace->filename }}</strong>
                    ({{ Str::upper(pathinfo($attachmentToReplace->filename, PATHINFO_EXTENSION)) }},
                    {{ \Illuminate\Support\Number::fileSize($attachmentToReplace->size, precision: 2) }})
                </span>
            @endif
        </flux:heading>

        <form wire:submit.prevent="processReplace" class="mt-6"
              x-data="{ 
                  uploadingReplace: false, 
                  progressReplace: 0, 
                  previewUrlReplace: null, 
                  isImageReplace: false
              }"
              x-on:livewire-upload-start="uploadingReplace = true"
              x-on:livewire-upload-finish="uploadingReplace = false; progressReplace = 0;"
              x-on:livewire-upload-error="uploadingReplace = false; progressReplace = 0;"
              x-on:livewire-upload-progress="progressReplace = $event.detail.progress">
            
                <div x-show="previewUrlReplace" class="mb-4">
                    <template x-if="isImageReplace">
                        <a x-bind:href="previewUrlReplace" class="glightbox" data-gallery="replace-preview">
                            <img x-bind:src="previewUrlReplace" class="max-h-48 rounded-lg mx-auto object-contain" alt="{{ __('Replacement Preview') }}" />
                        </a>
                    </template>
                    <template x-if="!isImageReplace && previewUrlReplace">
                        <p class="text-sm text-center text-gray-500 dark:text-gray-400">{{ __('Preview not available for this file type.') }}</p>
                    </template>
                </div>
                <flux:field>
                    <flux:label for="replacementFile">{{ __('New File') }}</flux:label>
                    <flux:input
                        type="file"
                        id="replacementFile"
                        wire:model="replacementFile"
                        x-on:change="
                            const file = $event.target.files[0];
                            if (file) {
                                isImageReplace = file.type.startsWith('image/');
                                if (isImageReplace) {
                                    previewUrlReplace = URL.createObjectURL(file);
                                } else {
                                    previewUrlReplace = null;
                                }
                            }
                        "
                        accept="{{ \App\Facades\Settings::get('attachments_allowed_extensions', '') ? '.' . str_replace(',', ',.', \App\Facades\Settings::get('attachments_allowed_extensions', '')) : '*' }}"
                    />
                    @error('replacementFile')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <p>{{ __('Max file size:') }} {{ \Illuminate\Support\Number::fileSize(\App\Facades\Settings::get('attachments_max_upload_size_kb', 10240) * 1024, precision: 2) }}</p>
                    </div>

                    <div class="flex items-center space-x-3">
                        <flux:button
                            type="button"
                            variant="outline"
                            wire:click="closeReplaceModal"
                        >
                            {{ __('Cancel') }}
                        </flux:button>

                        <flux:button
                            type="submit"
                            icon="arrow-path"
                            x-show="!uploadingReplace"
                            wire:loading.attr="disabled"
                            wire:target="replacementFile"
                        >
                            {{ __('Replace File') }}
                        </flux:button>

                        <div x-show="uploadingReplace" class="flex items-center space-x-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="`width: ${progressReplace}%`"></div>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400" x-text="`${Math.round(progressReplace)}%`"></span>
                        </div>
                    </div>
                </div>
            </form>
        </flux:modal>
</div>
