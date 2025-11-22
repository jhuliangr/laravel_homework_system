<x-site-layout>
    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-md sm:rounded-lg flex justify-between items-center px-3 py-2">
                    Hello
                    @if ($teacher)
                        teacher!
                    @else
                        student!
                    @endif
                </div>
                <div class="mt-10">
                </div>
                <a href="{{ route('course.pick') }}">
                    <x-breeze.primary-button>
                        Enroll courses
                    </x-breeze.primary-button>
                </a>
                @if ($teacher)
                    <a href="{{ route('homework.search') }}">
                        <x-breeze.primary-button>
                            Search homeworks
                        </x-breeze.primary-button>
                    </a>
                @endif
            </div>
        </div>
    </x-app-layout>
</x-site-layout>
