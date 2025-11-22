<x-site-layout>
    <div class="w-full flex items-center justify-between p-3">
        <x-back-button />
    </div>
    @if ($courseStudents->isEmpty())
        <div class="text-center pt-5 font-bold rounded-full">
            <h3 class="px-3 py-2">No students enrolled in this course</h3>
        </div>
    @else
        <div class="flex flex-col p-3 bg-gray-100 mt-5 max-h-[50vh] overflow-y-auto rounded-xl w-full">
            <div class="font-bold grid grid-cols-2 bg-teal-300 my-1 px-5 py-1 rounded-xl cursor-pointer">
                <div>
                    Student Name
                </div>
                <div>
                    Email
                </div>
            </div>
            @foreach ($courseStudents as $student)
                <x-student-row :student="$student" :courseId="$courseId" />
            @endforeach
    @endif
    </div>
</x-site-layout>
