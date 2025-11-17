<x-site-layout>
    <div class="bg-gray-200 w-full flex items-center justify-end rounded-xl p-3">
        @include('course.create-course-form')
    </div>
    @if ($courses->isEmpty())
        <h3>No courses found</h3>
    @else
        <div class="flex flex-col p-3 bg-gray-100 mt-5 max-h-[50vh] overflow-y-auto rounded-xl">
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
