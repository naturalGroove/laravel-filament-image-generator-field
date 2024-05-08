<div x-data="{
    init() {
            let openModalListener = addEventListener('open-modal', (event) => {
                if (event.detail.id === 'generate-an-image') {
                    $store.generateImage.statePath = event.detail.statePath ?? null;
                    $store.generateImage.disk = event.detail.disk ?? null;
                    $store.generateImage.generator = event.detail.generator ?? null;

                    $dispatch('update-image-generator', { generator: event.detail.generator });
                }
            });

            let generatedImageUploadedListener = addEventListener('generated-image-uploaded', (event) => {
                $dispatch('close-modal', { id: 'generate-an-image' });
                this.isDownloading = false;

                $dispatch('set-generated-image', { uuid: event.detail.uuid, localFileName: event.detail.localFileName, statePath: event.detail.statePath });
            });
        },

        isGenerating: false,
        isEmpty: true,
        isDownloading: false,

        generateImage() {
            this.isGenerating = true;
            this.isEmpty = true;

            $wire.generateImage($store.generateImage.generator)
                .then(() => {
                    this.isGenerating = false;

                    $store.generateImage.selectedImage.url = $wire.url;

                    this.isEmpty = $wire.generatedImages.length === 0;

                    refreshFsLightbox();
                });
        },
        selectImage(key) {
            $wire.selectImage(key)
                .then(() => {
                    $store.generateImage.selectedImage.url = $wire.url;
                    document.getElementById('photo' + key).classList.add('!border-primary-500');
                });
        }
}" x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('backend', package: 'naturalgroove/filament-image-generator-field'))]" x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('backend', package: 'naturalgroove/filament-image-generator-field'))]">
    <x-filament::modal
        id="generate-an-image"
        width="{{ config('filament-image-generator-field.modal.width') ?? '6xl' }}"
        heading="true"
        displayClasses="generate-an-image"
        closeButton="true"
    >
        <x-slot name="header">
            <strong>{{ __('filament-image-generator-field::backend.modals.generate-an-image.title') }}</strong>
            ({{ $generatorName }})
        </x-slot>

        <div>
            @if ($isConfigurationOk)
                <div class="grid gap-8 lg:grid-cols-5">
                    <div class="rounded dark:bg-neutral-800 lg:col-span-3">
                        <div class="h-full overflow-hidden rounded-xl">
                            <div class="flex h-full items-center justify-center" x-show="isGenerating || isEmpty">
                                <svg viewBox="0 0 512 512" class="h-48 text-neutral-100" x-bind:class="isGenerating ? 'animate-[spin_5s_linear_infinite]' : ''">
                                    <use xlink:href="#sparkless"></use>
                                </svg>
                            </div>

                            <div class="@if (count($generatedImages) < 2) flex items-center justify-center h-full @else h-full grid p-4 grid-cols-2 @if (count($generatedImages) > 2) grid-rows-2 @endif @endif place-content-center gap-4"
                                x-show="!isGenerating && !isEmpty"
                            >
                                @if (count($generatedImages) === 1)
                                    <a
                                        data-fslightbox="image-generator"
                                        data-type="image"
                                        href="{{ $url }}"
                                        class=""
                                    >
                                        <img src="{{ $url }}" class="ring-primary mx-auto rounded-xl object-contain" />
                                    </a>
                                @elseif (count($generatedImages) > 1)
                                    @foreach ($generatedImages as $key => $generatedImage)
                                        <div x-show="!isGenerating">
                                            <a data-fslightbox="image-generator" data-type="image" href="{{ $generatedImage['url'] }}">
                                                <img src="{{ $generatedImage['url'] }}" class="rounded-xl border-4 border-transparent object-contain" id="photo{{ $key }}" />
                                            </a>

                                            <x-filament::button @click.prevent="selectImage({{ $key }})" color="success" class="mt-2 w-full text-xs">
                                                @lang('filament-image-generator-field::backend.modals.generate-an-image.select')
                                            </x-filament::button>
                                        </div>
                                    @endforeach
                                @else
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="mb-8 rounded-lg bg-blue-100 px-4 py-3 text-blue-900 shadow-sm dark:bg-blue-800 dark:text-blue-100" role="alert">
                            <div class="flex">
                                <p class="text-sm">
                                    {!! __('filament-image-generator-field::backend.modals.generate-an-image.description') !!}
                                </p>
                            </div>
                        </div>

                        {{ $this->promptForm }}

                        <div class="mt-4 flex flex-col-reverse">
                            <x-filament::button x-on:click="generateImage()" class="btn-ig-generate" x-bind:disabled="isGenerating">
                                <div class="flex gap-4">
                                    <x-filament::loading-indicator class="h-5 w-5" x-show="isGenerating"></x-filament::loading-indicator>

                                    <svg viewBox="0 0 576 512" class="h-5 w-5" x-show="!isGenerating">
                                        <use xlink:href="#magic-wand"></use>
                                    </svg>

                                    <span x-show="!isGenerating">{{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.generate')) }}</span>
                                    <span x-show="isGenerating">{{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.generating')) }}</span>
                                </div>
                            </x-filament::button>
                        </div>
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
                                x-text="isDownloading ? '{{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.uploading')) }}' : '{{ Str::ucfirst(__('filament-image-generator-field::backend.modals.generate-an-image.add-generated')) }}'"
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
