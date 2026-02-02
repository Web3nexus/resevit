<x-filament-panels::page>
    <div class="max-w-3xl">
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament-schemas::actions :actions="$this->getFormActions()" />
            </div>
        </form>
    </div>
</x-filament-panels::page>