<x-site-layout>
    <div class="px-5 mb-5">
        @include('components.back-button', ['route' => 'student.enrolled', 'param1' => $courseId])
    </div>
    <div class="bg-gray-300 p-5 rounded-xl flex flex-col gap-3 w-full max-w-md mx-auto">
        <div class="flex gap-2 items-center justify-center">Student: <p class="font-bold">
                {{ $student->name }}
            </p>
        </div>
        <div class="flex gap-2 items-center justify-center">Email: <p class="font-bold">
                {{ $student->email }}
            </p>
        </div>
    </div>
    @if ($homeworksUploadedInCourse)
        @if ($homeworksUploadedInCourse->isEmpty())
            <div class="text-center pt-5 font-bold rounded-full">
                <h3 class="px-3 py-2">No homework submitted in this course</h3>
            </div>
        @else
            <div class="flex flex-col p-3 bg-gray-100 mt-5 max-h-[50vh] overflow-y-auto rounded-xl w-full">
                <div class="font-bold grid grid-cols-2 bg-teal-300 my-1 px-5 py-1 rounded-xl">
                    <div>
                        Homework Title
                    </div>
                    <div>
                        Grade
                    </div>
                </div>
                @foreach ($homeworksUploadedInCourse as $hw)
                    <x-homework-row :hw="$hw" />
                @endforeach
                {{ $homeworksUploadedInCourse->links() }}
            </div>
        @endif
    @endif
</x-site-layout>
