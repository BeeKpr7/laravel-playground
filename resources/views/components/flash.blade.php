@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="fixed flex px-4 py-6 mb-4 space-x-2 text-center text-green-800 bg-green-100 rounded-lg text-md w-96 top-3 right-3 dark:bg-gray-800 dark:text-green-400">
        <x-heroicon-s-check-circle class="inline-block w-5 h-5" />
        <p>{{ session('success') }}</p>

    </div>
@endif
