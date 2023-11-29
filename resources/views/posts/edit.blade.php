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
                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-12">
                            <div class="pb-12 border-b border-gray-900/10">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Update Post :
                                    {{ $post->title }}</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">
                                    Update your post here.
                                </p>

                                <div class="grid grid-cols-1 mt-10 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="first-name"
                                            class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                                        <div class="mt-2">
                                            <input value="{{ $post->title }}" type="text" name="title"
                                                id="title" autocomplete="title"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="body"
                                            class="block text-sm font-medium leading-6 text-gray-900">Body</label>
                                        <div class="mt-2">
                                            <textarea id="body" name="body" rows="3"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ $post->body }}</textarea>
                                        </div>
                                        <p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about
                                            the surf world.</p>
                                    </div>

                                    <div class="flex items-center justify-center space-x-2 col-span-full">
                                        @foreach ($post->getMedia('images') as $img)
                                            <img src="{{ $img->getUrl() }}" alt="" class="w-1/4" />
                                        @endforeach
                                    </div>

                                    <div class="col-span-full">
                                        <label for="cover-photo"
                                            class="block text-sm font-medium leading-6 text-gray-900">Cover
                                            photo</label>
                                        <input type="file" name="images[]"
                                            class="flex items-center justify-center px-5 py-10 bg-blue-500 grow-0 rounded-xl filepond"
                                            multiple>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="flex items-center justify-end mt-6 gap-x-6">
                            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                            <button type="submit"
                                class="px-3 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                        </div>
                    </form>
                    @if ($errors->any())
                        <div class="flex justify-end mb-4 col-span-full">
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-error-50 ">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
