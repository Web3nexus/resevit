<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit="save">
            {{ $this->translationForm }}

            <div class="mt-4 flex justify-end">
                <x-filament::button type="submit">
                    Save Translations
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>