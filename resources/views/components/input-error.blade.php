@props(['messages'])

@if ($messages)
    <p {{ $attributes->merge(['class' => 'mt-2 text-sm text-red-600']) }}>
        @foreach ((array) $messages as $message)
            <span>{{ $message }}</span>@if (! $loop->last)<br>@endif
        @endforeach
    </p>
@endif
