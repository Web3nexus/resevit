<x-filament-panels::page>
    @if((!$website && !$selectedTemplate) || $browsingTemplates)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex justify-between items-center">
                    <span>Choose a Template</span>
                    @if($browsingTemplates)
                        <x-filament::button color="gray" size="sm" wire:click="cancelBrowsing">
                            Cancel
                        </x-filament::button>
                    @endif
                </div>
            </x-slot>
            <x-slot name="subheading">
                Select a starting point for your restaurant website
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($templates as $template)
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer {{ ($selectedTemplate?->id === $template->id) ? 'ring-2 ring-primary-500' : '' }}"
                        wire:click="selectTemplate({{ $template->id }})">
                        <div class="aspect-16/10 bg-gray-100 dark:bg-gray-900 relative overflow-hidden">
                            @if($template->thumbnail_path)
                                <img src="{{ \App\Helpers\StorageHelper::getUrl($template->thumbnail_path) }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <x-heroicon-o-photo class="w-12 h-12 opacity-20" />
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <span class="text-white text-sm font-bold">Click to Select</span>
                            </div>
                        </div>
                        <div class="p-5 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $template->name }}</h3>
                            <x-heroicon-m-chevron-right class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @elseif((!$website && $selectedTemplate))
        <div class="max-w-2xl mx-auto">
            <x-filament::section class="text-center py-8">
                <div class="flex justify-center mb-6">
                    <div class="p-4 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                        <x-heroicon-o-sparkles class="w-12 h-12 text-primary-600" />
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ready to launch {{ $selectedTemplate->name }}?</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-8">
                    We'll initialize your website with professional content tailored for your business. You can customize every detail in the next step.
                </p>
                <div class="flex gap-4 justify-center">
                    <x-filament::button color="gray" wire:click="$set('selectedTemplate', null)">
                        Browse Templates
                    </x-filament::button>
                    <x-filament::button wire:click="createWebsite" size="lg">
                        Start Building
                    </x-filament::button>
                </div>
            </x-filament::section>
        </div>
    @else
        <div class="space-y-6">
            <x-filament::section>
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-success-50 dark:bg-success-900/20 rounded-xl">
                            <x-heroicon-o-check-circle class="w-8 h-8 text-success-600" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Your Website is Live</h2>
                            <p class="text-gray-500 text-sm">Active Template: <span class="text-primary-600 font-semibold">{{ $website->template->name }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <x-filament::button color="gray" tag="a" href="{{ route('tenant.home') }}" target="_blank"
                            icon="heroicon-o-arrow-top-right-on-square" class="flex-1 md:flex-none">
                            View Website
                        </x-filament::button>
                        <x-filament::button wire:click="toggleEditor" icon="{{ $isEditing ? 'heroicon-o-x-mark' : 'heroicon-o-pencil-square' }}" color="{{ $isEditing ? 'gray' : 'primary' }}" class="flex-1 md:flex-none">
                            {{ $isEditing ? 'Close Editor' : 'Edit Content' }}
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>

            @if($isEditing)
                <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-250px)]">
                    <!-- Editor Sidebar -->
                    <div class="w-full lg:w-1/3 overflow-y-auto pr-2 custom-scrollbar">
                        <x-filament::section>
                            <x-slot name="heading">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-pencil-square class="w-5 h-5 text-primary-500" />
                                    <span>Theme Editor</span>
                                </div>
                            </x-slot>
                            <x-filament-schemas::form wire:submit="save" class="space-y-6">
                                {{ $this->builderForm }}

                                <div class="sticky bottom-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md pt-4 border-t border-gray-100 dark:border-gray-800 z-10">
                                    <x-filament::button type="submit" size="lg" icon="heroicon-o-check" class="w-full shadow-lg shadow-primary-500/20">
                                        Save All Changes
                                    </x-filament::button>
                                </div>
                            </x-filament-schemas::form>
                        </x-filament::section>
                    </div>

                    <!-- Live Preview -->
                    <div class="hidden lg:block lg:w-2/3 bg-gray-100 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden relative group">
                        <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm border border-gray-100 dark:border-gray-700 z-10 flex items-center gap-3 transition-opacity duration-300">
                             <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                             </div>
                             <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Live Preview</span>
                        </div>
                        
                        <iframe 
                            src="http://{{ tenant('slug') }}.{{ config('tenancy.preview_domain') }}" 
                            class="w-full h-full border-none bg-white"
                            id="preview-iframe"
                        ></iframe>

                        <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                            <x-filament::button color="gray" size="sm" icon="heroicon-o-arrow-path" class="backdrop-blur-md" onclick="document.getElementById('preview-iframe').contentWindow.location.reload();">
                                Reload Preview
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <x-filament::section heading="Visual Preview">
                            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-inner bg-gray-100 dark:bg-gray-900">
                                @if($website->template->thumbnail_path)
                                    <img src="{{ \App\Helpers\StorageHelper::getUrl($website->template->thumbnail_path) }}"
                                        class="w-full h-auto opacity-75 grayscale-20">
                                @endif
                            </div>
                        </x-filament::section>
                    </div>
                    <div class="space-y-6">
                        <x-filament::section heading="Quick Actions">
                            <div class="space-y-3">
                                <x-filament::button wire:click="toggleEditor" icon="heroicon-o-pencil" color="gray" class="w-full justify-start">
                                    Edit Text & Images
                                </x-filament::button>
                                <x-filament::button wire:click="toggleEditor" color="gray" icon="heroicon-o-swatch" class="w-full justify-start">
                                    Change Theme Colors
                                </x-filament::button>
                                <x-filament::button color="gray" icon="heroicon-o-globe-alt" class="w-full justify-start" tag="a" href="/dashboard/whitelabel-settings" target="_blank">
                                    Custom Domain Settings
                                </x-filament::button>
                            </div>
                        </x-filament::section>
                        
                        <x-filament::section heading="Statistics">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Total Visits</span>
                                    <span class="font-bold">0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Reservations via Web</span>
                                    <span class="font-bold">0</span>
                                </div>
                            </div>
                        </x-filament::section>
                    </div>
                </div>
            @endif
        </div>
    @endif
</x-filament-panels::page>