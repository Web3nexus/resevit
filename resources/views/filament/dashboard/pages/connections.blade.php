<x-filament-panels::page>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- WhatsApp -->
        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-7 h-7" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">WhatsApp Business</h3>
            <p class="text-sm text-gray-500 mb-6">Connect to send automated reminders and chat with customers.</p>

            @if(isset($socialAccounts['whatsapp']))
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-sm font-medium text-green-600">Connected as
                        {{ $socialAccounts['whatsapp']->name }}</span>
                </div>
                <x-filament::button color="danger" wire:click="disconnectAction('whatsapp')">
                    Disconnect
                </x-filament::button>
            @else
                <x-filament::button color="success" tag="a"
                    href="{{ route('social.connect', ['platform' => 'whatsapp']) }}">
                    Connect WhatsApp
                </x-filament::button>
            @endif
        </div>

        <!-- Instagram -->
        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center mb-4">
                <x-heroicon-o-camera class="w-7 h-7" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Instagram</h3>
            <p class="text-sm text-gray-500 mb-6">Reply to DMs and manage comments directly from the dashboard.</p>

            @if(isset($socialAccounts['instagram']))
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-sm font-medium text-green-600">Connected as
                        {{ $socialAccounts['instagram']->name }}</span>
                </div>
                <x-filament::button color="danger" wire:click="disconnectAction('instagram')">
                    Disconnect
                </x-filament::button>
            @else
                <x-filament::button color="warning" tag="a"
                    href="{{ route('social.connect', ['platform' => 'instagram']) }}">
                    Connect Instagram
                </x-filament::button>
            @endif
        </div>

        <!-- Google Business -->
        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4">
                <x-heroicon-o-globe-alt class="w-7 h-7" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Google Business</h3>
            <p class="text-sm text-gray-500 mb-6">Sync reviews and business information.</p>

            @if(isset($socialAccounts['google']))
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-sm font-medium text-green-600">Connected as
                        {{ $socialAccounts['google']->name }}</span>
                </div>
                <x-filament::button color="danger" wire:click="disconnectAction('google')">
                    Disconnect
                </x-filament::button>
            @else
                <x-filament::button color="info" tag="a" href="{{ route('social.connect', ['platform' => 'google']) }}">
                    Connect Google
                </x-filament::button>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">API Settings</h3>
        <p class="text-gray-500 text-sm">Need to bring your own API keys? Use the <span
                class="font-mono text-primary-600">.env</span> configuration or contact support.</p>
    </div>

</x-filament-panels::page>