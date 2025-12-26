@php
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - ($fullStars + ($halfStar ? 1 : 0));
@endphp

<div class="flex items-center space-x-0.5 {{ $class ?? '' }}">
    @for($i = 0; $i < $fullStars; $i++)
        <i class="fas fa-star text-brand-accent"></i>
    @endfor

    @if($halfStar)
        <i class="fas fa-star-half-alt text-brand-accent"></i>
    @endif

    @for($i = 0; $i < $emptyStars; $i++)
        <i class="far fa-star text-slate-300"></i>
    @endfor
</div>