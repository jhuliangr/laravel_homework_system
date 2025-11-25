<x-site-layout>
    <x-app-layout>

        <div class="w-full flex items-center justify-between p-3">
            @include('components.back-button', ['route' => 'dashboard'])
            @if ($user->teacher)
                @include('components.course-form')
            @endif
        </div>
        @if ($courses->isEmpty())
            <div class="text-center pt-5 font-bold rounded-full">
                <h3 class="px-3 py-2">No courses found</h3>
            </div>
        @else
            <div class="flex flex-col p-3 bg-gray-100 mt-5 overflow-y-auto rounded-xl">
                <div class="font-bold grid grid-cols-2 bg-teal-300 my-1 px-5 py-1 rounded-xl cursor-pointer">
                    <div>
                        Course name
                    </div>
                    <div>
                        Start date
                    </div>
                </div>
                @foreach ($courses as $course)
                    <x-course-row :course="$course" />
                @endforeach
                {{ $courses->links() }}
            </div>
        @endif
    </x-app-layout>
</x-site-layout>
