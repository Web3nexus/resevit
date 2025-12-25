<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Email Settings Section --}}
        <div>
            {{ $this->emailForm }}

            <div class="mt-4 flex justify-end">
                <x-filament::button wire:click="saveEmail" type="button">
                    Save Email Settings
                </x-filament::button>
            </div>
        </div>

        {{-- SMS Settings Section --}}
        <div>
            {{ $this->smsForm }}

            <div class="mt-4 flex justify-end">
                <x-filament::button wire:click="saveSms" type="button">
                    Save SMS Settings
                </x-filament::button>
            </div>
        </div>
    </div>
    <x-filament-actions::modals />
</x-filament-panels::page>