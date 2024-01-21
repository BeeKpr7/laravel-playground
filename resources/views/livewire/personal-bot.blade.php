<div>
    <div>
        <form wire:submit.prevent="ask">

            <div class="flex gap-4">
                <!-- Input: Designation [h-12] & min-w-[12rem] -->
                <input
                    class="h-12 min-w-[12rem] rounded-lg border-emerald-500 indent-4 text-emerald-900 shadow-lg focus:outline-none focus:ring focus:ring-emerald-600"
                    type="text" name="question" wire:model="question" placeholder="What's up !" />
                <!-- Button: Submit [h-12] -->
                <button
                    class="h-12 min-w-[8rem] rounded-lg border-2 border-emerald-600 bg-emerald-500 text-emerald-50 shadow-lg hover:bg-emerald-600 focus:outline-none focus:ring focus:ring-emerald-600"
                    type="submit">
                    <span wire:loading.class="invisible">Go</span>
                    <x-spinner class="absolute invisible" wire:loading.class.remove="invisible" />
                </button>
            </div>
        </form>

        <div class="flex max-w-md gap-4">
            <h3 class="mt-8 mb-1 text-base font-semibold leading-6 text-gray-900">Me</h3>
            <div class="mb-2 prose">
                <div>{{ $question }}</div>
            </div>
            <h3 class="mt-8 mb-1 text-base font-semibold leading-6 text-gray-900">Jammy</h3>
            <div class="mb-2 prose">
                <div wire:stream='answer'>{!! $answer !!}</div>
            </div>
        </div>

    </div>
</div>
