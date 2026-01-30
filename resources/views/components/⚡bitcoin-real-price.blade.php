<?php

use Livewire\Component;

new class extends Component {
    public $price = 0;
    public $loading = true;
    public $error = false;

    protected $listeners = ['refreshBitcoinPrice' => '$refresh'];
    public function mount()
    {
        $this->fetchPrice();
    }

    public function fetchPrice()
    {
        $this->loading = true;
        $this->error = false;

        $url = 'https://api.api-ninjas.com/v1/bitcoin';
        $key = config('app.external_apis.api_ninja');

        try {
            $this->price = cache()->remember('bitcoin_price_key', now()->addMinutes(1), function () use ($url, $key) {
                return $this->fetchBitcoinPrice($url, $key);
            });
        } catch (\Throwable $th) {
            $this->price = 0;
            $this->error = true;
        } finally {
            $this->loading = false;
        }
    }
    private function fetchBitcoinPrice(string $url, string $key): float
    {
        $response = Http::withHeaders(['x-api-key' => $key])->get($url);
        $data = $response->json();
        return $data['price'] ?? 0;
    }
};
?>

@placeholder
    <div class="font-medium flex items-center">
        Bitcoin price:
        <div class="animate-spin size-5 border-teal-800"></div>
    </div>
@endplaceholder

<div class="font-medium flex items-center">
    @if ($loading)
        <div class="animate-pulse flex items-center">
            <div class="h-4 bg-gray-300 rounded w-24"></div>
            <div class="ml-2 h-4 bg-gray-300 rounded w-16"></div>
        </div>
    @elseif ($error)
        <span class="text-red-500 text-sm">Error loading price</span>
    @elseif ($price != 0)
        Bitcoin price:
        <p class="text-lime-700 px-2 text-sm">
            {{ number_format($price, 2) }}
        </p>
        <button wire:click="fetchPrice" wire:loading.attr="disabled" class="ml-2 text-gray-500 hover:text-gray-700"
            title="Refresh">
            <svg wire:loading.remove wire:target="fetchPrice" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <svg wire:loading wire:target="fetchPrice" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </button>
    @endif
</div>
