<x-site-layout>
    <div class="bg-gray-200 w-full flex items-center justify-between rounded-xl p-3">
        @include('components.back-button')
        @if ($courses->pluck('teacher_id')->contains($user->teacher->id))
            @if ($user->teacher)
                @include('course.create-course-form')
            @endif
        @endif
    </div>
    @if ($courses->isEmpty())
        <div class="text-center pt-5 font-bold rounded-full">
            <h3 class="px-3 py-2">No courses found</h3>
        </div>
    @else
        <div class="flex flex-col p-3 bg-gray-100 mt-5 max-h-[50vh] overflow-y-auto rounded-xl">
            <div class="font-bold grid grid-cols-2 bg-teal-300 my-1 px-5 py-1 rounded-xl cursor-pointer">
                <div>
                    Course name
                </div>
                <div>
                    Start date
                </div>
            </div>
            @foreach ($courses as $course)
                <a href="/course/{{ $course->id }}"
                    class="grid grid-cols-2 bg-teal-200 my-1 px-5 py-1 rounded-xl cursor-pointer hover:bg-teal-100 transition-colors duration-300 ease-in">
                    <div>
                        {{ $course->module_name }}
                    </div>
                    <div>
                        {{ $course->start_date }}
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-site-layout>
