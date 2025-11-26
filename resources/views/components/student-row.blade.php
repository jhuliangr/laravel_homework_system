<a 
    @if ($courseId) 
        href="/enrolled_student/{{ $courseId }}/{{ $student->id }}"
    @else 
        href="/enrolled_student/{{ $student->id }}" 
    @endif
    class="grid grid-cols-2 bg-gradient-to-r from-teal-200 to-teal-600 hover:from-teal-500 hover:to-teal-100 my-1 px-5 py-3 rounded-xl cursor-pointer shadow-md hover:shadow-sm"
>
    <div>
        {{ $student->name }}
    </div>
    <div>
        {{ $student->email }}
    </div>
</a>
