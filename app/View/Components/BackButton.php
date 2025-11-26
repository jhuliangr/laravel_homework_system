<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BackButton extends Component
{
    public string $prev = "";

    public function __construct()
    {
        $history = session()->get('history', []);

        // if there's not enough history, just use the built in back function
        $this->prev = count($history) >= 2 ?
            route('back.clean') :
            url()->previous();

    }


    public function render(): View|Closure|string
    {
        return view('components.back-button');
    }
}
