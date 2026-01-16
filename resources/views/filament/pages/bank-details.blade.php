<x-filament-panels::page>
    <div class="max-w-3xl">
        <x-filament-panels::form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex items-center gap-3">
                <x-filament::button type="submit">
                    Save Details
                </x-filament::button>
            </div>
        </x-filament-panels::form>
    </div>
</x-filament-panels::page>