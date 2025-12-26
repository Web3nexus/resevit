<x-filament-panels::page>
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Design Your Experience</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage your public website blocks and reservation
                    flows.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-filament::button wire:click="generateWithAi" icon="heroicon-o-sparkles" color="warning"
                    class="shadow-lg">
                    Generate with AI
                </x-filament::button>
                <x-filament::button wire:click="save" icon="heroicon-o-check" class="shadow-lg">
                    Save Changes
                </x-filament::button>
            </div>
        </div>

        <form wire:submit.prevent="save">
            {{ $this->form }}
        </form>

        <div class="bg-brand-primary/5 border border-brand-primary/10 rounded-2xl p-6 flex items-start space-x-4">
            <div class="bg-brand-primary/10 p-3 rounded-xl text-brand-primary">
                <x-heroicon-o-information-circle class="w-6 h-6" />
            </div>
            <div>
                <h4 class="font-bold text-brand-primary">Pro Tip: AI Website Generation</h4>
                <p class="text-sm text-brand-primary/70 mt-1">
                    Click "Generate with AI" to automatically create a high-converting layout based on your restaurant's
                    name, description, and menu. You can then fine-tune every block manually.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>