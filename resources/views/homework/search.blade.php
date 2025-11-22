<x-site-layout>
    <h1 class="mb-4 font-medium text-xl text-center">Búsqueda de Tareas</h1>

    <form action="{{ route('homework.search') }}" method="GET" class="flex items-center justify-between gap-5 px-5">
        <x-back-button />
        <x-breeze.text-input type="text" name="search" class="w-full"
            placeholder="Buscar por título, nombre de usuario, email o curso..." value="{{ $search }}" />
        <x-breeze.primary-button type="submit" class="btn btn-primary w-100">Buscar</x-breeze.primary-button>
    </form>

    @if ($homeworks->count() > 0)
        <div class="bg-gray-200 rounded-xl p-5 mt-5">
            <div class="grid grid-cols-5 w-full bg-white rounded-xl px-5 py-2">
                <div>Title</div>
                <div>Student</div>
                <div>Email</div>
                <div>Course</div>
                <div>Date</div>
            </div>
            @foreach ($homeworks as $homework)
                <a class="grid grid-cols-5 w-full bg-gradient-to-r from-teal-300 to-gray-400 rounded-xl px-5 py-2 my-1"
                    href="{{ route('homework.show', $homework->id) }}">
                    <div class="truncate">
                        {{ $homework->title }}
                    </div>
                    <div>
                        {{ $homework->student->name }}
                    </div>
                    <div>
                        {{ $homework->student->email }}
                    </div>
                    <div>
                        {{ $homework->course->module_name ?? '-' }}
                    </div>
                    <div>
                        {{ $homework->created_at->format('d/m/Y') }}
                    </div>
                </a>
            @endforeach

            <!-- Paginación -->
            {{ $homeworks->links() }}
        </div>
    @else
        <div class="alert alert-info">
            @if ($search)
                No homeworks matching "{{ $search }}"
            @else
                No homeworks available. Search for something.
            @endif
        </div>
    @endif
</x-site-layout>
