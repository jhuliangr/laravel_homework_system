<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeWorkController extends Controller
{
    function index(){
        return view('homework.index');
    }

    function show($id){
        return view('homework.show', compact('id'));
    }
}
