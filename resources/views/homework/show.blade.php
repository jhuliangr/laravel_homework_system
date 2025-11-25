<x-site-layout title="Homework">
    <div class="flex items-center justify-between mb-5 px-5">
        <x-back-button />
        <div class="flex gap-3 items-center">
            @if ($highestScore)
                <div
                    class="rounded-full bg-teal-800 p-3 text-white size-10 text-center flex items-center justify-center">
                    {{ $highestScore }}
                </div>
            @endif
            @if ($isTeacher)
                <x-evaluation-form :id="$homework->id" :evaluated="!$homework->evaluations->isEmpty()" highestScore="{{ $highestScore }}" />
            @else
                <p class="text-xl tracking-widest">
                    @if ($highestScore)
                        Points
                    @else
                        Not graded yet
                    @endif
                </p>
            @endif
        </div>
    </div>
    <div>
        <p class="text-xl font-bold p-5 text-center">
            {{ $homework->title }}
        </p>
        <div
            class="rounded-lg w-3/4 h-96 mx-auto p-4 border-2 bg-white border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none overflow-y-auto">
            {!! $homework->body !!}
        </div>
    </div>
</x-site-layout>
