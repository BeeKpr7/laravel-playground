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
                                    @foreach ($post->getMedia() as $img)
                                        <img class="flex-none w-12 h-12 rounded-full bg-gray-50"
                                            src="{{ $img->getUrl() }}" alt="">
                                    @endforeach
                                    <div class="flex-auto min-w-0">
                                        <p class="text-sm font-semibold leading-6 text-gray-900">Leslie Alexander</p>
                                        <p class="mt-1 text-xs leading-5 text-gray-500 truncate">
                                            leslie.alexander@example.com</p>
                                    </div>
                                </div>
                                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                    <p class="text-sm leading-6 text-gray-900">Co-Founder / CEO</p>
                                    <p class="mt-1 text-xs leading-5 text-gray-500">Last seen <time
                                            datetime="2023-01-23T13:23Z">3h ago</time>
                                    </p>
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
