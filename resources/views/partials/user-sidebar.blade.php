<aside class="w-64 bg-[#062B4A] text-white flex flex-col justify-between">
    <div>
      <div class="flex items-center justify-start h-20 border-b border-white/10">
        <img src="{{ asset('icon/bagologo.png') }}" class="h-12 w-12" />
        <span class="ml-2 font-bold">KALINAWKITA</span>
      </div>
      <nav class="mt-6 space-y-1 px-4">
        @role('Super_Admin')
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
    
        <a  wire:navigate 
            href="{{ route('superadmin-audittrails') }}"
            class="{{ request()->routeIs('superadmin-audittrails') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
        >
            Audit Trails
        </a>
        @endrole

     @role('BAC_Secretary')
          <a
          wire:navigate
          href="{{ route('bac-dashboard') }}"
          class="{{ request()->routeIs('bac-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 block"
      >
          Dashboard
      </a>
  
      <a
          wire:navigate
          href="{{ route('bac-procurement-planning') }}"
          class="{{ request()->routeIs('bac-procurement-planning') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
      >
          Procurement Planning
      </a>
  
      <a  wire:navigate
          href="{{ route('bac-bid-invitation') }}"
          class="{{ request()->routeIs('bac-bid-invitation') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
      >
          Bid Invitation
      </a>
      <a  wire:navigate
      href="{{ route('bac-bid-evaluation') }}"
      class="{{ request()->routeIs('bac-bid-evaluation') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
  >
      Bid Evaluation
      </a>
      <a  wire:navigate
      href="{{ route('notice-of-award') }}"
      class="{{ request()->routeIs('notice-of-award') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
    >
      Notice of Award
    </a>
     @endrole

     @role('Supplier')
            <a
            wire:navigate
            href="{{ route('supplier-dashboard') }}"
            class="{{ request()->routeIs('supplier-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 block"
        >
            Dashboard
        </a>

        <a
            wire:navigate
            href="{{ route('supplier-bid-initiation') }}"
            class="{{ request()->routeIs('supplier-bid-initiation') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
        >
            Bid Initiation
        </a>

        <a  wire:navigate
            href="{{ route('supplier-bid-participation') }}"
            class="{{ request()->routeIs('supplier-bid-participation') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
        >
            Bid Participation
        </a>
        <a  wire:navigate
        href="{{ route('supplier-bid-evaluation') }}"
        class="{{ request()->routeIs('supplier-bid-evaluation') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
        >
        Bid Evaluation
        </a>
        <a  wire:navigate
        href="{{ route('notice-of-award') }}"
        class="{{ request()->routeIs('notice-of-award') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
        >
        Notice of Award
        </a>
        @endrole

        @role('Purchaser')
        <a
        wire:navigate
        href="{{ route('purchaser-dashboard') }}"
        class="{{ request()->routeIs('purchaser-dashboard') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} font-normal rounded-full px-4 py-2 block"
    >
        Dashboard
    </a>

    <a
        wire:navigate
        href="{{ route('purchaser-procurement-planning') }}"
        class="{{ request()->routeIs('purchaser-procurement-planning') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
    >
        Procurement Planning
    </a>

    <a  wire:navigate
        href="{{ route('purchaser-bid-monitoring') }}"
        class="{{ request()->routeIs('purchaser-bid-monitoring') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
    >
        Bid Monitoring
    </a>
    <a  wire:navigate
    href="{{ route('inspection-report') }}"
    class="{{ request()->routeIs('inspection-report') ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black' }} rounded-full px-4 py-2 block"
    >
    Inspection Report
    </a>
    @endrole
    </nav>
   
    
    </div>
    <div class="px-4 py-4">
      <a href="#" class="hover:bg-[#EFE8A5] hover:text-black rounded-full px-4 py-2 block">Settings</a>
      <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <button type="submit" class="hover:bg-[#EFE8A5] hover:text-black rounded-full px-4 py-2 w-full text-left">
            Logout
        </button>
    </form>    
    </div>
  </aside>