<x-filament-panels::page>
    <form wire:submit="register" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end gap-3">
            <x-filament::button type="submit" size="lg">
                Create Account & Business
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>