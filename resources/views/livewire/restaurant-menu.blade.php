<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans">

    <!-- Hero / Header -->
    <header class="relative h-64 bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div
            class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center items-center text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight mb-2">
                {{ tenant('id') }}'s Menu
            </h1>
            <p class="text-lg text-gray-200 max-w-2xl">
                Experience culinary excellence with our curated selection of dishes.
            </p>
        </div>
    </header>

    <!-- Navigation / Categories -->
    <div
        class="sticky top-0 z-10 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex overflow-x-auto space-x-8 py-4 no-scrollbar">
                @foreach($categories as $category)
                            <button wire:click="$set('activeCategoryId', {{ $category->id }})" class="whitespace-nowrap px-1 py-2 border-b-2 text-sm font-medium transition-colors
                                    {{ $activeCategoryId == $category->id
                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                    : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' 
                                    }}">
                                {{ $category->name }}
                            </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @foreach($categories as $category)
            <div id="category-{{ $category->id }}"
                class="mb-12 scroll-mt-24 {{ $activeCategoryId && $activeCategoryId != $category->id ? 'hidden' : '' }}">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white flex items-center">
                    <span class="w-1 h-8 bg-indigo-500 rounded-full mr-3"></span>
                    {{ $category->name }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($category->menuItems as $item)
                        <div
                            class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <!-- Image -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200 h-48 w-full overflow-hidden">
                                @if($item->image_path)
                                    <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) }}" alt="{{ $item->name }}"
                                        class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">
                                        {{ $item->name }}
                                    </h3>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                        ${{ number_format($item->base_price, 2) }}
                                    </span>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                    {{ $item->description }}
                                </p>

                                <button wire:click="addToCart({{ $item->id }})"
                                    class="w-full py-2 px-4 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add to Order
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </main>

    <!-- Cart Floating Action Button / Slide Over -->
    @if(count($cart) > 0)
        <div class="fixed bottom-6 right-6 z-50">
            <button wire:click="$toggle('isCartOpen')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg flex items-center gap-3 transition-transform hover:scale-105">
                <div class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        {{ count($cart) }}
                    </span>
                </div>
                <span class="font-bold">View Order</span>
            </button>
        </div>
    @endif

    <!-- Cart Slide-over (simplified) -->
    @if($isCartOpen)
        <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="$toggle('isCartOpen')"></div>
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-900 shadow-xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-white" id="slide-over-title">Your
                                        Order</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500"
                                            wire:click="$toggle('isCartOpen')">
                                            <span class="absolute -inset-0.5"></span>
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="flow-root">
                                        <ul role="list" class="-my-6 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($cart as $item)
                                                <li class="flex py-6">
                                                    <div
                                                        class="h-24 w-24 shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                                                        <!-- Placeholder img or item img -->
                                                        <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                                                            <span class="text-2xl">🍔</span>
                                                        </div>
                                                    </div>

                                                    <div class="ml-4 flex flex-1 flex-col">
                                                        <div>
                                                            <div
                                                                class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                                                                <h3>{{ $item['name'] }}</h3>
                                                                <p class="ml-4">
                                                                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                                                </p>
                                                            </div>
                                                            <p class="mt-1 text-sm text-gray-500">{{ $item['quantity'] }}x</p>
                                                        </div>
                                                        <div class="flex flex-1 items-end justify-between text-sm">
                                                            <button type="button"
                                                                wire:click="removeFromCart('{{ $item['id'] }}')"
                                                                class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-6 sm:px-6">
                                <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                                    <p>Subtotal</p>
                                    <p>$0.00</p>
                                    <!-- TODO: Calculate subtotal -->
                                </div>
                                <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                                <div class="mt-6">
                                    <a href="{{ route('tenant.checkout') }}"
                                        class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>