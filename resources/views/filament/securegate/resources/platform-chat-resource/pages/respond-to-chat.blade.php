<x-filament-panels::page>
    @if(session()->has('error'))
        <div class="bg-danger-500/10 border border-danger-500 text-danger-500 p-4 rounded-xl mb-4 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif
    <livewire:admin-chat-response :conversation="$record" />
</x-filament-panels::page>