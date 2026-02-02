<x-filament-panels::page>
    <form wire:submit="save" class="gap-y-6">
        {{ $this->settingsForm }}

        <div class="mt-4 text-right">
            <x-filament::button type="submit">
                Save changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>