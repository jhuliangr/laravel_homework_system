<x-site-layout title="Homework">
    <div class="flex items-center justify-between mb-5 px-5">
        <x-back-button />
        <div class="flex gap-3 items-center">
            <div class="rounded-full bg-teal-800 p-3 text-white size-10 text-center flex items-center justify-center">
                {{ $highestScore }}
            </div>
            @if ($isTeacher)
                <x-evaluation-form :id="$homework->id" :evaluated="!$homework->evaluations->isEmpty()" highestScore="{{ $highestScore }}" />
            @else
                <p class="text-xl tracking-widest">
                    Points
                </p>
            @endif
        </div>
    </div>
    <div class="text-center">
        <p class="text-xl font-bold p-5">
            {{ $homework->title }}
        </p>
        <textarea
            class="rounded-lg w-3/4 h-96 p-4 border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none overflow-y-auto"
            readonly>
          {{ $homework->body }}
        </textarea>

    </div>
</x-site-layout>
