<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($materials as $material)
            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="text-lg font-bold mb-2">{{ $material->title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">{{ $material->description }}</p>

                @if($material->type === 'image')
                    <img src="{{ Storage::url($material->file_path) }}" alt="{{ $material->title }}"
                        class="w-full h-48 object-cover rounded mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Image Asset</span>
                        <a href="{{ Storage::url($material->file_path) }}" download
                            class="text-primary-600 hover:underline text-sm font-medium">Download</a>
                    </div>
                @elseif($material->type === 'link')
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded mb-4 break-all">
                        <code class="text-sm text-primary-600">{{ $material->url }}?ref={{ $influencer->referral_code }}</code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Referral Link</span>
                        <x-filament::button tag="a" href="{{ $material->url }}?ref={{ $influencer->referral_code }}"
                            target="_blank" size="xs">
                            Open Link
                        </x-filament::button>
                    </div>
                @elseif($material->type === 'text')
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded mb-4">
                        <p class="text-sm">{{ $material->description }}</p>
                    </div>
                    <button x-data
                        x-on:click="window.navigator.clipboard.writeText('{{ $material->description }}'); $tooltip('Copied!', { timeout: 1500 });"
                        class="text-primary-600 hover:underline text-sm font-medium">
                        Copy Text
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</x-filament-panels::page>