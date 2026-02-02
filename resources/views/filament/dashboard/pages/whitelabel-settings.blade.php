<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->brandingForm }}

        <div class="mt-6">
            <x-filament::button type="submit">
                Save Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>