<x-filament-panels::page>
    <x-filament-schemas::form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">
                Save Changes
            </x-filament::button>
        </div>
    </x-filament-schemas::form>
</x-filament-panels::page>