<div class="relative inline-block text-left px-4" x-data="{ open: false }">
    <div>
        <button type="button" @click="open = !open"
            class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200"
            id="menu-button" aria-expanded="true" aria-haspopup="true">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                    clip-rule="evenodd" />
            </svg>
            {{ $branches->firstWhere('id', $currentBranchId)?->name ?? 'All Branches' }}
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 dark:bg-gray-800"
        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <div class="py-1" role="none">
            <button wire:click="switchBranch('all')"
                class="text-gray-700 block px-4 py-2 text-sm w-full text-left hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                role="menuitem">
                All Branches
            </button>
            @foreach($branches as $branch)
                <button wire:click="switchBranch({{ $branch->id }})"
                    class="{{ $currentBranchId == $branch->id ? 'bg-gray-100 text-gray-900 font-bold dark:bg-gray-700' : 'text-gray-700' }} block px-4 py-2 text-sm w-full text-left hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    role="menuitem">
                    {{ $branch->name }}
                </button>
            @endforeach
        </div>
    </div>
</div>