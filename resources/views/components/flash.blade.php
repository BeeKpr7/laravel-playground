@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="fixed inset-x-0 w-64 p-4 mb-4 text-sm text-center text-green-800 rounded-lg top-3 right-3 bg-green-50 dark:bg-gray-800 dark:text-green-400">
        {{ session('success') }}
    </div>
@endif
