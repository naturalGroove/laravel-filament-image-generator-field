<x-dynamic-component :component="$getFieldWrapperView('generator')" :field="$field" :label-sr-only="$isLabelHidden()">

    <div class="grid grid-cols-2 gap-6" x-data="{
        init() {
            let setGeneratedImageListener = addEventListener('set-generated-image', (event) => {
                if (event.detail.statePath === '{{ $getStatePath() }}') {
                    $wire.set('{{ $getStatePath() }}', {
                        [event.detail.uuid]: event.detail.localFileName
                    });
                }
            });
        },
    }">
        <div class="">
            @include('filament-forms::components.file-upload')
        </div>

        <div class="">
            <x-filament::button
                x-on:click="$dispatch('open-modal', { id: 'generate-an-image', statePath: '{{ $getStatePath() }}', disk: '{{ $getDiskName() }}', generator: '{{ $getImageGenerator() }}'});"
                color="" class="btn-ig-generate"
            >
                <div class="flex gap-4">
                    <svg viewBox="0 0 576 512" class="h-5 w-5 text-white">
                        <use xlink:href="#magic-wand"></use>
                    </svg>

                    {{ Str::ucfirst(__('filament-image-generator-field::backend.labels.generate-using-ai')) }}
                </div>
            </x-filament::button>

            <x-filament::button
                x-on:click="$dispatch('open-modal', { id: 'edit-an-image', statePath: '{{ $getStatePath() }}' });"
                color=""
                class="btn-ig-edit"
                x-bind:disabled="$wire.get('{{ $getStatePath() }}').length == 0"
                x-show="false"
            >
                <div class="flex gap-4">
                    <svg viewBox="0 0 448 512" class="h-5 w-5 text-white">
                        <use xlink:href="#cauldron"></use>
                    </svg>

                    {{ Str::ucfirst(__('filament-image-generator-field::backend.labels.edit-using-ai')) }}
                </div>
            </x-filament::button>
        </div>
    </div>
</x-dynamic-component>
