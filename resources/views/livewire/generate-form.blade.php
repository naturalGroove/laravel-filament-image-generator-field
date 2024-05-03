<div x-data="{
    init() {
            let openModalListener = addEventListener('open-modal', (event) => {
                if (event.detail.id === 'generate-an-image') {
                    $store.generateImage.statePath = event.detail.statePath ?? null;
                    $store.generateImage.disk = event.detail.disk ?? null;
                    $store.generateImage.generator = event.detail.generator ?? null;
                }
            });

            let generatedImageUploadedListener = addEventListener('generated-image-uploaded', (event) => {
                $dispatch('close-modal', { id: 'generate-an-image' });
                this.isDownloading = false;

                $dispatch('set-generated-image', { uuid: event.detail.uuid, localFileName: event.detail.localFileName, statePath: event.detail.statePath });
            });
        },

        isGenerating: false,
        isDownloading: false,

        generateImage() {
            this.isGenerating = true;

            $wire.generateImage($store.generateImage.generator)
                .then(() => {
                    this.isGenerating = false;

                    $store.generateImage.selectedImage.url = $wire.url;

                    refreshFsLightbox();
                });
        }
}" x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('backend', package: 'naturalgroove/filament-image-generator-field'))]" x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('backend', package: 'naturalgroove/filament-image-generator-field'))]">
    <x-filament::modal
        id="generate-an-image"
        width="3xl"
        heading="true"
        displayClasses="generate-an-image"
        closeButton="true"
    >
        <x-slot name="header">
            <strong>{{ __('filament-image-generator-field::backend.modals.generate-an-image.title') }}</strong>
        </x-slot>

        <div>
            @if ($isConfigurationOk)
                <div class="mb-8 rounded-lg bg-blue-100 px-4 py-3 text-blue-900 shadow-sm" role="alert">
                    <div class="flex">
                        <p class="text-sm">
                            {!! __('filament-image-generator-field::backend.modals.generate-an-image.description') !!}
                        </p>
                    </div>
                </div>

                <div class="aspect-h-9 aspect-w-16 bg-neutral-100">
                    <div class="flex items-center justify-center">
                        @if ($url)
                            <a
                                data-fslightbox="image-generator"
                                data-type="image"
                                href="{{ $url }}"
                                class="h-full"
                            >
                                <img src="{{ $url }}" class="mx-auto h-full" />
                            </a>
                        @else
                            <svg viewBox="0 0 512 512" class="h-1/2 text-white" x-bind:class="isGenerating ? 'animate-[spin_5s_linear_infinite]' : ''">
                                <use xlink:href="#sparkless"></use>
                            </svg>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    {{ $this->promptForm }}

                    <div class="mt-4 flex flex-col-reverse">
                        <x-filament::button x-on:click="generateImage()" class="btn-ig-generate" x-bind:disabled="isGenerating">
                            <div class="flex gap-4">
                                <x-filament::loading-indicator class="h-5 w-5" x-show="isGenerating"></x-filament::loading-indicator>

                                <svg viewBox="0 0 576 512" class="h-5 w-5" x-show="!isGenerating">
                                    <use xlink:href="#magic-wand"></use>
                                </svg>

                                {{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.generate')) }}
                            </div>
                        </x-filament::button>
                    </div>
                </div>
            @else
                <div class="mb-8 rounded-lg bg-red-400 px-4 py-3 text-white shadow-sm" role="alert">
                    <div class="flex">
                        <p class="text-sm">
                            {!! __('filament-image-generator-field::backend.modals.generate-an-image.configuration-error') !!}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <x-slot name="footer">
            <div class="flex flex-row-reverse justify-between">
                <div class="flex">
                    <x-filament::button color="gray" class="mr-2" x-on:click="isOpen = false">
                        {{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.cancel')) }}
                    </x-filament::button>

                    <x-filament::button
                        color="primary"
                        @click="$dispatch('add-selected', { image: $store.generateImage.selectedImage, statePath: $store.generateImage.statePath, disk: $store.generateImage.disk, generator: $store.generateImage.generator }); isDownloading = true;"
                        x-show="$store.generateImage?.selectedImage.url !== null"
                        x-bind:disabled="isDownloading"
                    >
                        <div class="flex gap-4">
                            <x-filament::loading-indicator class="h-5 w-5" x-show="isDownloading"></x-filament::loading-indicator>

                            <span
                                x-text="isDownloading ? '{{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.downloading')) }}' : '{{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.add-generated')) }}'"
                            ></span>

                        </div>
                    </x-filament::button>
                </div>
            </div>
        </x-slot>
    </x-filament::modal>

    @include('filament-image-generator-field::partials.svg-defs')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('generateImage', {
                selectedImage: {
                    url: null,
                },
                statePath: null,
                disk: null,
                generator: null,
            })
        })
    </script>
</div>
