<x-breeze.primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'evaluation-form')"
    class="text-gray-950 bg-teal-300 py-3 px-5 rounded-xl hover:bg-teal-400 transition-colors duration-300 shadow-md hover:shadow-sm">
    @if ($reEvaluate)
        Revaluate
    @else
        Evaluate
    @endif
    homework
</x-breeze.primary-button>
<x-breeze.modal name="evaluation-form" :show="$errors->isNotEmpty()" focusable>

    <form method="post" action="{{ route($reEvaluate ? 'homework.reEvaluate' : 'homework.evaluate', $id) }}"
        class="p-6">
        @csrf
        <div class="grid grid-cols-2 gap-3 p-5">
            <label class="relative flex items-center cursor-pointer">
                <input class="sr-only peer" name="evaluation" type="radio" value="5"
                    {{ $score == 5 ? 'checked' : '' }} />
                <div
                    class="w-6 h-6 bg-transparent border-2 border-green-500 rounded-full peer-checked:bg-green-500 peer-checked:border-green-500 peer-hover:shadow-lg peer-hover:shadow-green-500/50 peer-checked:shadow-lg peer-checked:shadow-green-500/50 transition duration-300 ease-in-out">
                </div>
                <span class="ml-2 text-black">Excellent</span>
            </label>
            <label class="relative flex items-center cursor-pointer">
                <input class="sr-only peer" name="evaluation" type="radio" value="3"
                    {{ $score == 3 ? 'checked' : '' }} />
                <div
                    class="w-6 h-6 bg-transparent border-2 border-yellow-500 rounded-full peer-checked:bg-yellow-500 peer-checked:border-yellow-500 peer-hover:shadow-lg peer-hover:shadow-yellow-500/50 peer-checked:shadow-lg peer-checked:shadow-yellow-500/50 transition duration-300 ease-in-out">
                </div>
                <span class="ml-2 text-black">Pass</span>
            </label>
            <label class="relative flex items-center cursor-pointer">
                <input class="sr-only peer" name="evaluation" type="radio" value="2"
                    {{ $score == 2 ? 'checked' : '' }} />
                <div
                    class="w-6 h-6 bg-transparent border-2 border-red-500 rounded-full peer-checked:bg-red-500 peer-checked:border-red-500 peer-hover:shadow-lg peer-hover:shadow-red-500/50 peer-checked:shadow-lg peer-checked:shadow-red-500/50 transition duration-300 ease-in-out">
                </div>
                <span class="ml-2 text-black">Bad</span>
            </label>
            <label class="relative flex items-center cursor-pointer">
                <input class="sr-only peer" name="evaluation" type="radio" value="0"
                    {{ $score == 0 ? 'checked' : '' }} />
                <div
                    class="w-6 h-6 bg-transparent border-2 border-gray-500 rounded-full peer-checked:bg-gray-500 peer-checked:border-gray-500 peer-hover:shadow-lg peer-hover:shadow-gray-500/50 peer-checked:shadow-lg peer-checked:shadow-gray-500/50 transition duration-300 ease-in-out">
                </div>
                <span class="ml-2 text-black">Unevaluated</span>
            </label>
        </div>
        <div class="flex justify-between">
          <x-breeze.secondary-button x-on:click="$dispatch('close')">
            Cancel
          </x-breeze.secondary-button>
            <x-breeze.primary-button type="submit">
                Submit evaluation
            </x-breeze.primary-button>
        </div>
    </form>

</x-breeze.modal>
