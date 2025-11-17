<section class="space-y-6">
    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'course-form')"
        class="text-gray-950 bg-teal-300 py-3 px-5 rounded-xl">
        {{ isset($course) ? 'Edit course':'Create Course' }}
    </button>

    <x-breeze.modal name="course-form" :show="$errors->isNotEmpty()" focusable>
        <form method="post" action="{{ isset($course) ? route('course.update', $course) : route('course.store') }}" class="p-6">
            @csrf
            @if(isset($course))
                @method('PUT')
            @endif

            <h2 class="text-lg font-medium text-gray-900">
                {{ isset($course) ? 'Edit Course' : 'Create Course' }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ isset($course) ? 'Edit the course information below.' : 'By creating this course you will be creating one course :D' }}
            </p>

            <div class="mt-6 flex flex-col justify-center items-center gap-3">
                <x-breeze.input-label for="module_name" value="Module name" class="sr-only" />
                <x-breeze.text-input id="module_name" name="module_name" type="text" class="mt-1 block w-3/4"
                    placeholder="Beautiful module name" 
                    value="{{ old('module_name', isset($course) ? $course->module_name : '') }}" />

                @error('module_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-breeze.input-label for="start_date" value="Start date" class="sr-only" />
                <x-breeze.text-input id="start_date" name="start_date" type="date" class="mt-1 block w-3/4"
                    value="{{ old('start_date', isset($course) ? $course->start_date : '') }}" />

                @error('start_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-6 flex justify-between">
                <x-breeze.secondary-button x-on:click="$dispatch('close')" type="button">
                    Cancel
                </x-breeze.secondary-button>

                <button class="ms-3 py-3 px-5 bg-teal-300 rounded-xl">
                    {{ isset($course) ? 'Update Course' : 'Create Course' }}
                </button>
            </div>
        </form>
    </x-breeze.modal>
</section>