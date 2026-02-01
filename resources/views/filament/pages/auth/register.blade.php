<div>
    <x-auth-split-layout :heading="$this->getHeading()" :subheading="$this->getSubheading()">
        <form wire:submit="register" class="grid gap-y-8" id="register-form">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                Register
            </x-filament::button>
        </form>
    </x-auth-split-layout>
</div>