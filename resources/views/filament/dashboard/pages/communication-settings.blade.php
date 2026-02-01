<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-4 flex justify-end">
            <x-filament::button type="submit">
                Save Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>