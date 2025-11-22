<x-site-layout>
    <div class="w-full flex items-center justify-between p-3 mb-5">
        @include('components.back-button')
        @if ($edit)
            @include('components.course-form')
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
                @if ($course->students->isEmpty())
                    <form action="{{ route('course.destroy', $course->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 py-2 px-10 rounded-xl text-white">
                            Delete
                        </button>
                    </form>
                @else
                    <div class="flex gap-5">

                        <a href="{{ route('student.enrolled', $course->id) }}">
                            <x-breeze.primary-button>
                                View enrolled students
                            </x-breeze.primary-button>
                        </a>
                        <a href="{{ route('homework.index', $course->id) }}">
                            <x-breeze.primary-button>
                                View submitted homeworks
                            </x-breeze.primary-button>
                        </a>
                    </div>
                @endif
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
                <div class="flex items-center justify-center gap-5">
                    <a href="{{ route('homework.index', $course->id) }}">
                        <x-breeze.primary-button
                            class="text-gray-950 bg-teal-300 py-3 px-5 rounded-xl hover:bg-teal-400 transition-colors duration-300 shadow-md hover:shadow-sm">
                            Show uploaded homeworks
                        </x-breeze.primary-button>
                    </a>
                    @include('components.homework-form', ['id' => $course->id])
                </div>
            @endif
        @endif
    </div>
</x-site-layout>
