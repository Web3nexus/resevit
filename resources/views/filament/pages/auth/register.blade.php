<div>
    <x-auth-split-layout :heading="$this->getHeading()" :subheading="$this->getSubheading()">
        <x-filament-schemas::form wire:submit="register" class="grid gap-y-8" id="register-form">
            {{ $this->form }}
        </x-filament-schemas::form>
    </x-auth-split-layout>
</div>