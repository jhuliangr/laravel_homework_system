<!DOCTYPE html>
<html>
<head>
    <title>{{$title}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<header class="bg-teal-300 p-5 flex items-center justify-between fixed w-full z-10">
    <p class="text-xl">
        Homework
    </p>
    <div class="group">
        <div class="group-hover:bg-slate-300 border-t-4 border-b-2 border-teal-700 w-5 h-3"></div>
        <div class="group-hover:bg-slate-300 border-t-2 border-b-4 border-teal-700 w-5 h-3"></div>
    </div>
</header>    

<div class="max-w-screen px-10 bg-teal-50 py-20 flex-1">
    {{$slot}}
</div>
<footer class="bg-teal-200 w-full p-3">
    Homework @ All rights reserved
</footer>

</html>