<div class="font-medium flex items-center">
    @if ($price != 0)
        Bitcoin price:
        <p class="text-lime-700 px-2 text-sm">
            {{ number_format($price, 2) }}
        </p>
    @endif
</div>
