<div x-data="{
    open: false,
    showAnnouncementModal: false,
    selectedAnnouncement: null,
    openModal(id, type) {
        // Only open modal for announcement
        if(type === 'announcement') {
            const notif = @this.notifications.find(n => n.id === id && n.type === 'announcement');
            if(notif) {
                this.selectedAnnouncement = notif;
                this.showAnnouncementModal = true;
            }
        }
    },
    closeModal() {
        this.showAnnouncementModal = false;
        this.selectedAnnouncement = null;
    }
}" class="relative" wire:poll.2s="loadCounts">

    <!-- Bell Button -->
    <button @click="open = !open" class="text-gray-500 hover:text-black relative">
        <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />

        @if($ppmpUnreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                {{ $ppmpUnreadCount }}
            </span>
            <span class="animate-ping absolute -top-1 -right-1 block h-2 w-2 rounded-full ring-2 ring-red-400 bg-red-600"></span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" @click.away="open = false" x-transition
        class="absolute right-0 mt-2 w-96 bg-white border rounded-lg shadow-lg overflow-hidden z-50">

        <div class="p-4 border-b font-semibold text-gray-700">Notifications</div>

        <ul class="max-h-80 overflow-y-auto divide-y">
            @forelse($notifications as $notif)
                <li 
                    class="w-full px-4 py-3 flex items-start gap-3 rounded-md transition {{ !$notif['is_read'] ? 'bg-yellow-50' : '' }}"
                    @click="$wire.markSingleAsRead('{{ $notif['type'] }}', {{ $notif['id'] }}); openModal({{ $notif['id'] }}, '{{ $notif['type'] }}')"
                >
                    <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                        @if($notif['type'] === 'ppmp')
                            <x-phosphor.icons::regular.file-text class="w-5 h-5 text-indigo-500" />
                        @else
                            <x-phosphor.icons::regular.megaphone class="w-5 h-5 text-red-500" />
                        @endif
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-semibold flex items-center justify-between {{ !$notif['is_read'] ? 'text-gray-900' : 'text-gray-800' }}">
                            @if($notif['type'] === 'ppmp')
                                New PPMP: {{ $notif['title'] }}
                            @else
                                Announcement: {{ $notif['title'] }}
                            @endif

                            @if(!$notif['is_read'])
                                <span class="ml-2 inline-block bg-red-500 text-white text-xs px-1.5 py-0.5 rounded">NEW</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($notif['type'] === 'ppmp')
                                Requested by: {{ $notif['requested_by'] ?? 'N/A' }}
                            @else
                                {{ $notif['message'] }}
                            @endif
                        </p>
                    </div>
                </li>
            @empty
                <li class="px-4 py-3 text-center text-sm text-gray-500">
                    No notifications found
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Announcement Modal -->
    <div x-show="showAnnouncementModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4" x-transition>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 transform transition-all duration-300 scale-95"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <!-- Header -->
            <div class="flex items-start justify-between border-b pb-3 mb-4">
                <h3 x-text="selectedAnnouncement ? selectedAnnouncement.title : ''" class="text-lg font-bold text-gray-900"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="space-y-3">
                <p x-text="selectedAnnouncement ? selectedAnnouncement.message : ''" class="text-gray-700 text-sm leading-relaxed"></p>
                <p x-text="selectedAnnouncement ? new Date(selectedAnnouncement.date).toLocaleDateString() : ''" class="text-xs text-gray-500 italic"></p>
            </div>

            <!-- Footer -->
            <hr class="mt-5">
            <div class="mt-4 text-gray-600 text-sm space-y-1">
                <p>This announcement is from Kalinaw.</p>
                <p>Be sure to check all details and follow the instructions provided.</p>
            </div>

            <div class="mt-6 flex justify-end">
                <button @click="closeModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    Close
                </button>
            </div>

        </div>
    </div>

</div>
