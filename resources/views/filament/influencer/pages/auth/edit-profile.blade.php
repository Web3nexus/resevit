<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Profile Information
        </x-slot>

        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-4 text-right">
                <x-filament::button type="submit">
                    Save Profile
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Two-Factor Authentication
        </x-slot>

        <div>
            {{ $this->twoFactorForm }}
        </div>
    </x-filament::section>
</x-filament-panels::page>