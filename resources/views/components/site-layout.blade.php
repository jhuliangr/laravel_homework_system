<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-teal-50">
    <header class="bg-teal-300 p-5 flex items-center justify-between fixed w-[95vw] z-10 shadow-lg rounded-full mt-2 mx-[2.5vw]">
        <a href="{{ Auth::check() ? '/dashboard' : '/' }}">
            <p class="text-xl select-none">
                <x-breeze.application-logo class="size-10 text-gray-500" />
            </p>
        </a>

        <div class="relative">
            <button id="dropdownButton" class="p-2 rounded hover:bg-teal-400 transition-colors">
                <div class="border-t-4 border-b-2 border-teal-700 w-7 h-3 rounded-t-sm"></div>
                <div class="border-t-2 border-b-4 border-teal-700 w-7 h-3 rounded-b-sm"></div>
            </button>

            <div id="dropdownMenu"
                class="absolute right-0 mt-2 w-48 bg-white text-gray-700 rounded-md shadow-sm py-1 hidden z-20">
                @if (Route::has('login'))
                    <div class="flex flex-col items-start justify-end">
                        @auth
                            <a href="{{ url('/profile') }}" class="block px-4 py-2 hover:bg-teal-100 w-full">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}"
                                class="block px-4 py-2 hover:bg-teal-100 w-full">
                                @csrf
                                <button 
                                class="w-full text-left"
                                :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-teal-100 w-full">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-teal-100 w-full">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div id="overlay" class="fixed inset-0 z-0 hidden"></div>

    <div class="max-w-screen px-10 py-20 flex-1 pt-28">
        {{ $slot }}
    </div>
    <footer class="bg-teal-200 w-[95vw] p-3 text-center rounded-t-full mx-[2.5vw] flex items-center justify-center gap-3">
        Homework @ All rights reserved 
        <livewire:bitcoin-price />
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('dropdownButton');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const overlay = document.getElementById('overlay');

            dropdownButton.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
                overlay.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!dropdownMenu.contains(e.target) && !dropdownButton.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                    overlay.classList.add('hidden');
                }
            });

            overlay.addEventListener('click', function() {
                dropdownMenu.classList.add('hidden');
                overlay.classList.add('hidden');
            });

            dropdownMenu.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    dropdownMenu.classList.add('hidden');
                    overlay.classList.add('hidden');
                }
            });
        });
    </script>

</body>

</html>
