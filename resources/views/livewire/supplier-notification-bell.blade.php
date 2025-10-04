<div>
    <div 
        x-data="{ open: false }" 
        class="relative"
        wire:poll.5s="loadNotifications"
    >
        <!-- 🔔 Bell Button -->
        <button 
            @click="open = !open; if(open) { $wire.markAsRead(); }" 
            class="relative text-gray-600 hover:text-black"
        >
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />

            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                    {{ $unreadCount }}
                </span>
                <span class="animate-ping absolute -top-1 -right-1 block h-2 w-2 rounded-full ring-2 ring-red-400 bg-red-600"></span>
            @endif
        </button>

        <!-- 📄 Dropdown -->
        <div 
            x-show="open" 
            @click.away="open = false" 
            x-transition
            class="absolute right-0 mt-2 w-96 bg-white border rounded-lg shadow-lg overflow-hidden z-50"
        >
            <div class="p-4 border-b font-semibold text-gray-700">
                Supplier Notifications
            </div>

            <ul class="max-h-80 overflow-y-auto divide-y">
                @forelse($notifications as $notif)
                    <li 
                        class="px-4 py-3 flex items-start gap-3 transition 
                        {{ $notif['is_read'] ? '' : 'bg-yellow-50' }}"
                    >
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            @if($notif['type'] === 'accepted')
                                <x-phosphor.icons::regular.check-circle class="w-5 h-5 text-green-600" />
                            @else
                                <x-phosphor.icons::regular.file-text class="w-5 h-5 text-indigo-500" />
                            @endif
                        </div>

                        <!-- Text -->
                        <div class="flex-1">
                            @if($notif['type'] === 'accepted')
                                <p class="text-sm text-gray-700 leading-snug">
                                    Your proposal for <strong>{{ $notif['title'] }}</strong> was 
                                    <strong class="text-green-600">accepted</strong>.
                                </p>
                            @else
                                <p class="text-sm text-gray-700 leading-snug">
                                    New invitation: <strong>{{ $notif['title'] }}</strong>
                                </p>
                            @endif
                            <span class="text-xs text-gray-500">
                                {{ $notif['created_at'] }}
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-3 text-center text-sm text-gray-500">
                        No notifications available
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
