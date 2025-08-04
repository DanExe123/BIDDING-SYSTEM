<aside class="w-64 bg-[#062B4A] text-white flex flex-col justify-between">
    <div>
      <div class="flex items-center justify-start h-20 border-b border-white/10">
        <img src="icon/bagologo.png" class="h-12 w-12" />
        <span class="ml-2 font-bold">KALINAWKITA</span>
      </div>
      <nav class="mt-6 space-y-1 px-4">
        <a
            wire:navigate
            href="{{ route('superadmin-dashboard') }}"
            class="{{ request()->routeIs('superadmin-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 block"
        >
            Dashboard
        </a>
    
        <a
            wire:navigate
            href="{{ route('superadmin-user-management') }}"
            class="{{ request()->routeIs('superadmin-user-management') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
        >
            User Management
        </a>
    
        <a
            href="#"
            class="rounded-full px-4 py-2 block hover:bg-[#EFE8A5] hover:text-black"
        >
            Audit Trails
        </a>
    </nav>
    
    </div>
    <div class="px-4 py-4">
      <a href="#" class="hover:bg-[#EFE8A5] hover:text-black rounded-full px-4 py-2 block">Settings</a>
    </div>
  </aside>