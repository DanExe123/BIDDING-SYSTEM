<div>
    <div 
        x-data="{ open: false }" 
        class="relative"
        wire:poll.2s="loadCounts"
    >
        <!-- Bell Button -->
        <button 
            @click="open = !open; if(open) { $wire.markAsRead(); }" 
            class="text-gray-500 hover:text-black relative"
        >
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />

            <!-- Badge -->
            @if($ppmpUnreadCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                    {{ $ppmpUnreadCount }}
                </span>
                <span class="animate-ping absolute -top-1 -right-1 block h-2 w-2 rounded-full ring-2 ring-red-400 bg-red-600"></span>
            @endif
        </button>

        <!-- Dropdown -->
        <div 
            x-show="open" 
            @click.away="open = false" 
            x-transition
            class="absolute right-0 mt-2 w-96 bg-white border rounded-lg shadow-lg overflow-hidden z-50"
        >
            <div class="p-4 border-b font-semibold text-gray-700">PPMP Notifications</div>

            <ul class="max-h-80 overflow-y-auto divide-y">
                @forelse($ppmpNotifications as $ppmp)
                <li class="px-4 py-3 flex items-start gap-3 rounded-md transition {{ $ppmp['is_read'] ? '' : 'bg-yellow-50' }}">
                    <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                        <x-phosphor.icons::regular.file-text class="w-5 h-5 text-indigo-500" />
                    </div>
                    <p class="text-sm text-gray-700 leading-snug">
                        New PPMP: <strong>{{ $ppmp['title'] }}</strong><br>
                        <span class="text-xs text-gray-500">Requested by: {{ $ppmp['requested_by'] }}</span>
                    </p>
                </li>                
                @empty
                    <li class="px-4 py-3 text-center text-sm text-gray-500">
                        No new PPMP notifications
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
