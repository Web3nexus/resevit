<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Input Section -->
        <div>
            <x-filament::section>
                <x-filament-schemas::form wire:submit="generateImage">
                    {{ $this->form }}

                    <div class="flex gap-4 mt-6">
                        <x-filament::button wire:click="refinePrompt" color="info" icon="heroicon-o-sparkles"
                            type="button" wire:loading.attr="disabled" wire:target="refinePrompt">
                            Refine Prompt
                        </x-filament::button>

                        <x-filament::button type="submit" icon="heroicon-o-cpu-chip" wire:loading.attr="disabled"
                            wire:target="generateImage">
                            Generate Image
                        </x-filament::button>
                    </div>
                </x-filament-schemas::form>

                <div wire:loading wire:target="refinePrompt" class="mt-4 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <x-filament::loading-indicator class="h-5 w-5" />
                        <span>Enhancing your prompt with AI magic...</span>
                    </div>
                </div>

                <div wire:loading wire:target="generateImage" class="mt-4 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <x-filament::loading-indicator class="h-5 w-5" />
                        <span>Creating your masterpiece (this may take a few seconds)...</span>
                    </div>
                </div>
            </x-filament::section>
        </div>

        <!-- Result Section -->
        <div>
            <x-filament::section heading="Generated Result">
                @if($generatedImageUrl)
                    <div class="space-y-4">
                        <div
                            class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                            <img src="{{ $generatedImageUrl }}" alt="Generated Image" class="w-full h-auto object-cover" />

                            <div
                                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <x-filament::button tag="a" href="{{ $generatedImageUrl }}" target="_blank" color="gray"
                                    icon="heroicon-o-arrow-top-right-on-square">
                                    Open Full Size
                                </x-filament::button>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900 unrounded p-4 text-sm text-gray-600 dark:text-gray-400">
                            <strong>Prompt Used:</strong><br>
                            {{ $data['prompt'] }}
                        </div>
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg">
                        <x-heroicon-o-photo class="w-16 h-16 mb-4 opacity-50" />
                        <p class="text-center">Your generated image will appear here.</p>
                        <p class="text-xs mt-2">Try refining your prompt for better results!</p>
                    </div>
                @endif
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>