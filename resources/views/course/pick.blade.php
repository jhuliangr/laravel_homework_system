<x-site-layout>
    <div class="bg-gray-200 w-full flex items-center justify-between rounded-xl p-3">
        @include('components.back-button')
        <h2 class="font-bold px-5 text-xl">Select the courses you want to enroll in</h2>
        <div></div>
    </div>
    @if ($courses->isEmpty())
        <div class="text-center pt-5 font-bold rounded-full">
            <h3 class="px-3 py-2">No courses available</h3>
        </div>
    @else
        <div class="flex flex-col p-3 bg-gray-100 mt-5 max-h-[50vh] overflow-y-auto rounded-xl">
            <div class="font-bold grid grid-cols-2 bg-teal-300 my-1 px-5 py-1 rounded-xl">
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
        </div>
        
    {{ $courses->links() }}
    @endif
</x-site-layout>
