<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;

class BitcoinPrice extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $url = "https://api.api-ninjas.com/v1/bitcoin";
        $key = config('app.external_apis.api_ninja');

        try {
            $price = cache()->remember('bitcoin_price_key', now()->addMinutes(1), function () use ($url, $key) {
                return $this->fetchBitcoinPrice($url, $key);
            });
        } catch (\Throwable $th) {
            $price = 0;
        }

        return view('components.bitcoin-price', compact('price'));
    }

    private function fetchBitcoinPrice(string $url, string $key): float
    {
        $response = Http::withHeaders(['x-api-key' => $key])->get($url);
        $data = $response->json();
        return $data['price'] ?? 0;
    }
}

