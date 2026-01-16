<x-filament-panels::page>
    <div class="max-w-3xl">
        <x-filament-schemas::form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament-schemas::actions :actions="$this->getFormActions()" />
            </div>
        </x-filament-schemas::form>
    </div>
</x-filament-panels::page>