@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="fixed flex w-64 p-4 mb-4 space-x-2 text-sm text-center text-green-800 rounded-lg bottom-3 right-3 bg-green-50 dark:bg-gray-800 dark:text-green-400">
        <x-heroicon-s-check-circle class="inline-block w-5 h-5" />
        <p>{{ session('success') }}</p>

    </div>
@endif
