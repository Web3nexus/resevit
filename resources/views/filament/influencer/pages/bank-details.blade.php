<x-filament-panels::page>
    <x-filament-schemas::form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament-schemas::actions :actions="$this->getFormActions()" />
        </div>
    </x-filament-schemas::form>
</x-filament-panels::page>