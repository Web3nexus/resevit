<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary inline-flex items-center justify-center px-4 py-2 border border-transparent font-semibold text-sm uppercase tracking-wide disabled:opacity-50 transition']) }}>
    <span class="flex items-center justify-center px-2 py-1">{{ $slot }}</span>
</button>
