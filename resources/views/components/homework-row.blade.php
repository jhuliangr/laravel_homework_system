<div
    class="my-1 py-2 px-5 bg-gradient-to-r from-teal-200 to-teal-600 hover:from-teal-500 hover:to-teal-100 rounded-xl shadow-md hover:shadow-sm">
    <a href="/homework/show/{{ $hw->id }}" class="grid grid-cols-2">
        <h2 class="">{{ $hw->title }}</h2>
        <p>
            @if ($hw->evaluations->max('value') !== null)
                {{ $hw->evaluations->max('value') }}
            @else
                Not graded yet
            @endif
        </p>
        @if (isset($withStudentName))
            <p class="col-span-2 text-sm text-gray-700 mt-1">Submitted by:
                {{ $hw->student->userData->name }}
            </p>
        @else
        @endif
    </a>
</div>
