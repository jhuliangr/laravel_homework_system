<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    function index(){
        return view('article.index');
    }

    function show($article){
        return view('article.show', compact(var_name: 'article'));
    }
}
