<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackController extends Controller
{
    public function back()
    {
        $history = session()->get('history', []);

        if (count($history) >= 2) {
            array_pop($history);
            
            session()->put('history', $history);
        }

        $prev = count($history) > 0 ? array_pop($history) : url()->previous();
        return redirect($prev);
    }
}