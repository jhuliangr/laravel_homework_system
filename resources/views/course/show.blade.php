<x-site-layout>
    <div class="bg-gray-200 w-full flex items-center justify-between rounded-xl p-3 mb-5">
        @include('components.back-button')
        @if ($edit)
            @include('course.create-course-form')
        @endif
    </div>
    <div class="px-10">
        <div class="bg-gray-100 border rounded-xl p-3">
            {{ $course->module_name }}
        </div>
        <div class="bg-gray-100 border rounded-xl p-3 mt-2">
            {{ $course->start_date }}
        </div>
    </div>
    <div class="w-full h-52 flex items-center justify-center">
        @if ($edit)
            @if ($user->teacher)
                <form action="{{ route('course.destroy', $course->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 py-2 px-10 rounded-xl text-white">
                        Delete
                    </button>
                </form>
            @endif
        @else
            @if (!$user->courses->pluck('id')->contains($course->id))
                <form action="{{ route('course.enroll_in', $course->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-teal-500 py-2 px-10 rounded-xl text-white">
                        Enroll in
                    </button>
                </form>
            @else
                subir tarea :D
            @endif
        @endif
    </div>
</x-site-layout>
