<x-filament-panels::page>
    <div class="max-w-2xl mx-auto py-10">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">Setting up your restaurant</h1>
            <p class="text-slate-500">Follow these steps to complete your profile.</p>
        </div>

        <x-filament-schemas::form wire:submit="submit">
            {{ $this->form }}
        </x-filament-schemas::form>
    </div>
</x-filament-panels::page>