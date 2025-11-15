@props(['type' => 'success', 'message' => ''])

{{-- Floating toast container --}}
<div class="fixed top-5 right-5 z-50">
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         x-transition
         class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800"
         role="alert">

        {{-- Icon --}}
        @if ($type === 'success')
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
            </div>
        @endif

        {{-- Message --}}
        <div class="ms-3 text-sm font-normal">{{ $message }}</div>

        {{-- Close button --}}
        <button @click="show = false" type="button" 
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</div>