<div x-data="{ languageMenuOpen: false }" class="relative inline-block text-left flex-none">
    <button @click="languageMenuOpen = ! languageMenuOpen" type="button" class="flex flex-row items-center gap-2 px-3 py-1.5 focus:outline-none group hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors whitespace-nowrap min-w-max">
        <span class="text-xl leading-none filter drop-shadow-sm group-hover:scale-110 transition-transform flex-none">
            @switch(app()->getLocale())
                @case('es') 🇪🇸 @break
                @case('fr') 🇫🇷 @break
                @case('de') 🇩🇪 @break
                @case('ar') 🇸🇦 @break
                @default 🇺🇸
            @endswitch
        </span>
        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300 transition-colors duration-200 flex-none" :class="{ 'rotate-180': languageMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div 
        x-show="languageMenuOpen" 
        @click.away="languageMenuOpen = false" 
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50 {{ $direction === 'up' ? 'bottom-full mb-3 origin-bottom-right' : 'top-full mt-3 origin-top-right' }}" 
        style="display: none;"
    >
        <div class="py-1.5 focus:outline-none flex flex-col">
            @foreach($this->languages as $code => $data)
            <button wire:click="changeLocale('{{ $code }}')" @click="languageMenuOpen = false" 
                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ app()->getLocale() === $code ? 'bg-gray-50 dark:bg-gray-700/50 font-semibold text-primary-600 dark:text-primary-400' : '' }}">
                <span class="mr-3 text-lg leading-none flex-none">{{ $data[0] }}</span>
                <span class="grow text-left whitespace-nowrap">{{ __($data[1]) }}</span>
                @if(app()->getLocale() === $code)
                <svg class="w-4 h-4 text-primary-500 flex-none ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                @endif
            </button>
            @endforeach
        </div>
    </div>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
