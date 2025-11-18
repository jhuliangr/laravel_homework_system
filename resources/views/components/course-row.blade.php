<a href="/course/{{ $course->id }}"
    class="grid grid-cols-2 bg-gradient-to-r from-teal-200 to-teal-600 my-1 px-5 py-3 rounded-xl cursor-pointer hover:bg-teal-100 transition-colors duration-300 ease-in">
    <div>
        {{ $course->module_name }}
    </div>
    <div>
        {{ $course->start_date }}
    </div>
</a>
