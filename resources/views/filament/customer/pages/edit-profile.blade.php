<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Profile Information
        </x-slot>

        <x-filament-schemas::form wire:submit="updateProfile">
            {{ $this->profileForm }}

            <div class="mt-4 text-right">
                <x-filament::button type="submit">
                    Save Profile
                </x-filament::button>
            </div>
        </x-filament-schemas::form>
    </x-filament::section>

    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Security
        </x-slot>

        <x-filament-schemas::form wire:submit="updatePassword">
            {{ $this->passwordForm }}

            <div class="mt-4 text-right">
                <x-filament::button type="submit">
                    Update Password
                </x-filament::button>
            </div>
        </x-filament-schemas::form>
    </x-filament::section>
</x-filament-panels::page>