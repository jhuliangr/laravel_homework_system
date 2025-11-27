<x-site-layout>
    <div class="bg-white text-black flex p-6 items-center flex-col rounded-3xl shadow-lg w-[60vw] mx-auto">
        <div class="grid grid-cols-3">
            <div class="flex items-center justify-center">
                {!! $chart2->container() !!}
            </div>
            <main class="flex items-center justify-center">
                <div
                    class="text-[13px] leading-[20px] flex flex-col items-center p-6 bg-white dark:bg-[#161615] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <x-breeze.application-logo class="size-20" />
                    <h1 class="mb-1 font-medium text-xl">Let's get started</h1>
                    @auth
                        <a href="{{ route('dashboard') }}">
                            <x-breeze.primary-button>
                                Go to dashboard
                            </x-breeze.primary-button>
                        </a>
                    @else
                        <p class="mb-2 text-gray-500 text-center">To access our homework system you'll need an
                            account <br>We suggest starting with the following.</p>
                        <ul class="flex flex-col mb-4 lg:mb-6">
                            <li
                                class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] before:top-1/2 before:bottom-0 before:left-[0.4rem] before:absolute">
                                <span class="relative py-1 bg-white dark:bg-[#161615]">
                                    <span
                                        class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                        <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                                    </span>
                                </span>
                                <span>
                                    Talk to
                                    <a href="https://github.com/ndeblauw" target="_blank"
                                        class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-teal-600 ml-1">
                                        <span>Our teacher</span>
                                        <x-link-icon />
                                    </a>
                                </span>
                            </li>
                            <li
                                class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] before:bottom-1/2 before:top-0 before:left-[0.4rem] before:absolute">
                                <span class="relative py-1 bg-white dark:bg-[#161615]">
                                    <span
                                        class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                        <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                                    </span>
                                </span>
                                <span>
                                    Create an account
                                    <a href="{{ route('register') }}"
                                        class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-teal-600 ml-1">
                                        <span>Register</span>
                                        <x-link-icon />
                                    </a>
                                </span>
                            </li>
                        </ul>
                        <p class="pb-5">
                            or
                        </p>
                        <ul class="flex gap-3 text-sm leading-normal">
                            <li>
                                <a href="{{ route('login') }}"
                                    class="inline-block hover:bg-teal-400 px-5 py-2 bg-teal-600 rounded-xl text-white transition-colors duration-200 ease-in-out">
                                    Log in
                                </a>
                            </li>
                        </ul>
                        @endif
                    </div>
                </main>
                <div class="flex items-center justify-center">
                    <div>
                        {!! $chart->container() !!}
                    </div>
                </div>
            </div>

            @if (Route::has('login'))
                <div class="h-14.5 hidden lg:block"></div>
            @endif
        </div>
        <script src="{{ $chart->cdn() }}"></script>
        {{ $chart->script() }}
        {{ $chart2->script() }}
    </x-site-layout>
