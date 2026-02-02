<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament-schemas::actions :actions="$this->getFormActions()" />
        </div>
    </form>
</x-filament-panels::page>