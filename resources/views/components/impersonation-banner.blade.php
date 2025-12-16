@if (session()->has('impersonator_id'))
<div class="fixed bottom-4 right-4 z-50 p-4 bg-amber-500 text-white rounded shadow-lg flex items-center gap-4 dark:bg-amber-600">
    <div class="flex flex-col">
        <span class="font-bold uppercase text-xs">Impersonation Mode</span>
        <span class="text-sm">You are logged in as {{ auth()->user()->name }}</span>
    </div>

    <a href="{{ route('impersonate.leave') }}"
        class="px-3 py-1 bg-white text-amber-600 text-sm font-bold rounded hover:bg-gray-100 transition">
        Exit
    </a>
</div>
@endif