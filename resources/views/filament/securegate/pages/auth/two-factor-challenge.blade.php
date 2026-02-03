@php
    $heading = $this->getHeading();
@endphp

<div class="flex min-h-screen flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-900">
    <div
        class="w-full max-w-sm rounded-xl bg-white p-8 shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h2 class="mb-6 text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
            {{ $heading }}
        </h2>

        <p class="mb-6 text-center text-sm text-gray-500 dark:text-gray-400">
            @if ($usingRecoveryCode)
                Please enter one of your emergency recovery codes.
            @else
                Please confirm access to your account by entering the authentication code provided by your authenticator
                application.
            @endif
        </p>

        <form wire:submit="authenticate" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                Verify
            </x-filament::button>
        </form>

        <div class="mt-6 text-center text-sm">
            <button wire:click="toggleRecoveryCode" type="button"
                class="text-primary-600 hover:text-primary-500 dark:text-primary-400 font-medium">
                @if ($usingRecoveryCode)
                    Use an authentication code
                @else
                    Use a recovery code
                @endif
            </button>
        </div>
    </div>
</div>