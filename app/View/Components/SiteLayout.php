<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SiteLayout extends Component
{

    public function __construct(public string $title = "My lovely homework system")
    {
        $history = session()->get('history', []);
        $actualUrl = url()->current();

        // If the user reloads the page or if uses the back route not to add to the array 
        if (end($history) != $actualUrl && !str_contains($actualUrl, '/back')) {
            if (count($history) == 10) {
                array_shift($history);
            }

            $history[] = $actualUrl;
            session()->put('history', $history);
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.site-layout');
    }
}
