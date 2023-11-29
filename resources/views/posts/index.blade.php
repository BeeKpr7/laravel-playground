<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('posts.create') }}"
                        class="right-0 px-5 py-2 mb-5 text-sm font-semibold leading-6 text-gray-900 rounded-lg shadow-md bg-slate-400">Add
                        Post</a>
                    <ul role="list" class="mt-5 overflow-x-auto divide-y divide-gray-100 max-h-96">
                        @foreach ($posts as $post)
                            <li class="flex justify-between py-5 gap-x-6">
                                <div class="flex min-w-0 gap-x-4">
                                    @foreach ($post->getMedia('images') as $img)
                                        <img class="flex-none w-12 h-12 rounded-full bg-gray-50"
                                            src="{{ $img->getUrl() }}" alt="">
                                    @endforeach
                                    <div class="flex-auto min-w-0">
                                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ $post->title }}</p>
                                        <p class="mt-1 text-xs leading-5 text-gray-500 truncate">
                                            {{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div @mouseover="open = true" @mouseout="open = false" class="relative"
                                    x-data="{ open: false }">
                                    <button
                                        class="px-6 py-4 font-medium text-gray-500 dark:text-blue-700 hover:underline focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>

                                    </button>
                                    <ul class="absolute top-0 z-50 p-4 mt-12 space-y-3 font-medium text-gray-500 -translate-x-1/2 bg-gray-100 rounded-lg shadow-lg text-md dark:text-gray-400 dark:bg-gray-700"
                                        x-show="open">
                                        <li class="">
                                            <a href="{{ route('posts.edit', $post) }}"
                                                class="flex items-center justify-between space-x-2 font-medium text-blue-500 dark:text-blue-700 hover:underline">
                                                <p>{{ __('Edit') }}</p>
                                                <x-heroicon-s-pencil-square class="w-4 h-4" />
                                            </a>
                                        </li>
                                        <li class="">

                                            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="flex items-center justify-between space-x-2 font-medium text-red-500 dark:text-blue-700 hover:underline">
                                                    <p>{{ __('Delete') }}</p>
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-5">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
