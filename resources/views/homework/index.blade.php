<x-site-layout>
    @if ($homeworks->isEmpty())
        <h3>No homeworks found</h3>
    @else
        <div class="flex items-center justify-between mb-5">
            <x-back-button />
            @include('components.homework-form', ['id' => $courseId])
        </div>
        @foreach ($homeworks as $hw)
            @if (isset($withStudentName))
                <x-homework-row :hw="$hw" withStudentName="{{ $withStudentName }}" />
            @else
                <x-homework-row :hw="$hw" />
            @endif
        @endforeach
        {{ $homeworks->links() }}
    @endif
</x-site-layout>
