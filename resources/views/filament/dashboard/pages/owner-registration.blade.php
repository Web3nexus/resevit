<x-filament-panels::page>
    <x-filament-schemas::form wire:submit="register" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end gap-3">
            <x-filament::button type="submit" size="lg">
                Create Account & Business
            </x-filament::button>
        </div>
    </x-filament-schemas::form>
</x-filament-panels::page>