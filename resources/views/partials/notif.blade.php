<div 
x-data="{
    open: false,
    // init state
    unread: @entangle('unreadCount'),
    init() {
        // load unread from localStorage if exists
        let saved = localStorage.getItem('unreadCount');
        if (saved !== null) {
            this.unread = parseInt(saved);
        }
    },
    markAllAsRead() {
        this.unread = 0;
        localStorage.setItem('unreadCount', 0); // persist
        $wire.markAsRead(); // optional: call Livewire if you want server update
    }
}"
x-init="init()"
class="relative"
>
<!-- Bell Button -->
<button 
    @click="open = !open; if(open) { markAllAsRead(); }" 
    class="text-gray-500 hover:text-black relative"
>
    <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />

    <!-- Badge -->
    <span 
        x-show="unread > 0" 
        x-transition 
        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1"
        x-text="unread"
    ></span>
</button>

<!-- Dropdown -->
<div 
    x-show="open" 
    @click.away="open = false" 
    x-transition 
    class="absolute right-0 mt-2 w-96 bg-white border rounded-lg shadow-lg overflow-hidden z-50"
>
    <div class="p-4 border-b font-semibold text-gray-700">Notifications</div>

    <ul class="max-h-80 overflow-y-auto divide-y">
        @forelse($notifications as $notif)
            <li class="px-4 py-3 hover:bg-gray-100 flex items-center gap-3">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <x-dynamic-component 
                        :component="'phosphor.icons::regular.' . $notif['icon']" 
                        class="w-5 h-5 {{ $notif['color'] }}" 
                    />
                </div>
                <p class="text-sm text-gray-700 leading-snug">
                    New {{ $notif['role'] }}: <strong>{{ $notif['name'] }}</strong>
                </p>
            </li>
        @empty
            <li class="px-4 py-3 text-center text-sm text-gray-500">
                No new notifications
            </li>
        @endforelse
    </ul>
</div>
</div>