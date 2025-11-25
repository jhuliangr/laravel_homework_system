<section class="space-y-6">
    <x-breeze.primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'homework-form')"
        class="text-gray-950 bg-teal-300 py-3 px-5 rounded-xl hover:bg-teal-400 transition-colors duration-300 shadow-md hover:shadow-sm">
        Upload homework
    </x-breeze.primary-button>

    <x-breeze.modal name="homework-form" :show="$errors->isNotEmpty()" focusable>
        <form method="post" action="{{ route('homework.store', $id) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                Upload homework
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                By submitting this homework you will be a more responsible student.
            </p>

            <div class="mt-6 flex flex-col justify-center items-center gap-3">
                <x-breeze.input-label for="title" value="Title" class="sr-only" />
                <x-breeze.text-input id="title" name="title" type="text" class="mt-1 block w-3/4"
                    placeholder="Beautiful title" />

                @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-breeze.input-label for="body" value="Body" class="sr-only" />
                <x-breeze.text-area id="body" name="body" type="" class="mt-1 block w-3/4"
                    aria-placeholder="Beautiful body of your homework" rte />

                @error('body')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-6 flex justify-between">
                <x-breeze.secondary-button x-on:click="$dispatch('close')" type="button">
                    Cancel
                </x-breeze.secondary-button>

                <button class="ms-3 py-3 px-5 bg-teal-300 rounded-xl">
                    Send homework
                </button>
            </div>
        </form>
    </x-breeze.modal>
</section>
