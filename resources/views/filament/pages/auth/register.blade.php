<div>
    <x-auth-split-layout
        :heading="$this->getHeading()"
        :subheading="$this->getSubheading()">
        <form wire:submit="register" class="grid gap-y-8" id="register-form">
            {{ $this->form }}
        </form>
    </x-auth-split-layout>
</div>