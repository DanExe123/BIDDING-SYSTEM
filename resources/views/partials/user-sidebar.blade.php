<aside class="w-64 bg-[#062B4A] text-white flex flex-col justify-between" x-cloak>
    <div>
        <div class="flex items-center justify-start h-20 border-b border-white/10">
            <img src="{{ asset('icon/bagologo.png') }}" class="h-12 w-12" />
            <span class="ml-2 font-bold">KALINAWKITA</span>
        </div>
        <nav class="mt-6 space-y-1 px-4">
            @role('Super_Admin')
                <a wire:navigate href="{{ route('superadmin-dashboard') }}"
                    class="{{ request()->routeIs('superadmin-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.house class="w-5 h-5" />
                    Dashboard
                </a>

                <a wire:navigate href="{{ route('superadmin-user-management') }}"
                    class="{{ request()->routeIs('superadmin-user-management') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.users-three class="w-5 h-5" />
                    User Management
                </a>

                <a wire:navigate href="{{ route('superadmin-audittrails') }}"
                    class="{{ request()->routeIs('superadmin-audittrails') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.list-magnifying-glass class="w-5 h-5" />
                    Audit Trails
                </a>
            @endrole

            @role('BAC_Secretary')
                <a wire:navigate href="{{ route('bac-dashboard') }}"
                    class="{{ request()->routeIs('bac-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.house class="w-5 h-5" />
                    <span class="text-sm whitespace-nowrap">Dashboard</span>
                </a>

                <a wire:navigate href="{{ route('bac-procurement-planning') }}"
                    class="{{ request()->routeIs('bac-procurement-planning') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.clipboard-text class="w-5 h-5" />
                    <span class="text-sm whitespace-nowrap">Pruchase Request</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('bac-mode-of-procurement') || request()->routeIs('bac-procurement-workflow') || request()->routeIs('bac-competitive-bidding') ? 'true' : 'false' }} }" class="space-y-2">
                    <!-- Main Button -->
                    <button @click="open = !open" class=" rounded-full px-4 py-2 flex items-center gap-2 w-full">
                        <x-phosphor.icons::regular.clipboard-text class="w-5 h-5" />
                        <span class="text-sm whitespace-nowrap"> Procurement Planning</span>

                        <!-- Caret toggle -->
                        <template x-if="!open">
                            <x-phosphor.icons::regular.caret-down class="w-5 h-5" />
                        </template>
                        <template x-if="open">
                            <x-phosphor.icons::regular.caret-up class="w-5 h-5" />
                        </template>
                    </button>

                    <!-- Submenu (stays open if route matches) -->
                    <div x-show="open" x-transition class="mt-2 ml-8 space-y-2">
                        <a href="{{ route('bac-mode-of-procurement') }}"
                            class="{{ request()->routeIs('bac-mode-of-procurement') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                            <x-phosphor.icons::regular.hand-coins class="w-5 h-5" />
                            <span class="text-sm whitespace-nowrap">Mode of Procurement</span>
                        </a>

                        <a href="{{ route('bac-procurement-workflow') }}"
                            class="{{ request()->routeIs('bac-procurement-workflow') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                            <x-phosphor.icons::regular.file-text class="w-5 h-5" />
                            <span class="text-sm whitespace-nowrap">Precurement Workflow</span>
                        </a>
                    </div>

                </div>

                <a wire:navigate href="{{ route('bac-notice-of-award') }}"
                    class="{{ request()->routeIs('bac-notice-of-award') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.trophy class="w-5 h-5" />
                    <span class="text-sm whitespace-nowrap">Notice of Award</span>
                </a>
            @endrole

            @role('Supplier')
                <a wire:navigate href="{{ route('supplier-dashboard') }}"
                    class="{{ request()->routeIs('supplier-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.house class="w-5 h-5" />
                    Dashboard
                </a>

                <a wire:navigate href="{{ route('supplier-invitations') }}"
                    class="{{ request()->routeIs('supplier-invitations') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.file-plus class="w-5 h-5" />
                    Inviations
                </a>

                <a wire:navigate href="{{ route('supplier-proposal-submission') }}"
                    class="{{ request()->routeIs('supplier-proposal-submission') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.handshake class="w-5 h-5" />
                    Proposal Submission
                </a>

                <a wire:navigate href="{{ route('supplier-notice-of-award') }}"
                    class="{{ request()->routeIs('supplier-notice-of-award') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.trophy class="w-5 h-5" />
                    Notice of Award
                </a>
            @endrole

            @role('Purchaser')
                <a wire:navigate href="{{ route('purchaser-dashboard') }}"
                    class="{{ request()->routeIs('purchaser-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.house class="w-5 h-5" />
                    Dashboard
                </a>

                <a wire:navigate href="{{ route('purchaser-procurement-planning') }}"
                    class="{{ request()->routeIs('purchaser-procurement-planning') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.clipboard-text class="w-5 h-5" />
                    Purchase Order
                </a>

                <a wire:navigate href="{{ route('purchaser-bid-monitoring') }}"
                    class="{{ request()->routeIs('purchaser-bid-monitoring') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.chart-line class="w-5 h-5" />
                    Purchase Request
                </a>

                {{--    <a wire:navigate href="{{ route('inspection-report') }}"
                    class="{{ request()->routeIs('inspection-report') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 flex items-center gap-2">
                    <x-phosphor.icons::regular.file-magnifying-glass class="w-5 h-5" />
                    Inspection Report
                </a> --}}
            @endrole
        </nav>


    </div>
    <div class="px-4 py-4">
        <a wire:navigate href="{{ route('user-help') }}" class="hover:bg-[#EFE8A5] hover:text-black rounded-full px-4 py-2 flex items-center gap-2">
            <x-phosphor.icons::regular.info class="w-5 h-5" />
            Help
        </a>  
        <a wire:navigate href="{{ route('settings.profile') }}" class="hover:bg-[#EFE8A5] hover:text-black rounded-full px-4 py-2 flex items-center gap-2">
            <x-phosphor.icons::regular.gear class="w-5 h-5" />
            Settings
        </a>        
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                class="hover:bg-[#EFE8A5] hover:text-black rounded-full px-4 py-2 w-full text-left flex items-center gap-2">
                <x-phosphor.icons::regular.sign-out class="w-5 h-5" />
                Logout
            </button>
        </form>
    </div>

</aside>
