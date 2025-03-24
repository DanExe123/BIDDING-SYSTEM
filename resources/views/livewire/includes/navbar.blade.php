<!-- Menu Items (Moved to Left) -->
<div 
    x-show="open" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    @click.away="open = false" 
    class="absolute bottom-0 left-20 mr-2 bg-gray-800 shadow-lg rounded-lg p-4"
>
    <nav class="flex space-x-6">
        <!-- Home -->
        <a wire:navigate.prevent href="{{ route('home') }}" 
           class="relative text-white font-semibold text-lg transition-all duration-300 hover:text-red-200 
           after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-red-700 hover:after:w-full after:transition-all after:duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2h-4a2 2 0 01-2-2v-6H9v6a2 2 0 01-2 2H3a2 2 0 01-2-2V9z"></path>
            </svg>
        </a>
        <!-- About -->
        <a wire:navigate.prevent href="{{ route('about') }}" 
           class="relative text-white font-semibold text-lg transition-all duration-300 hover:text-red-400 
           after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-red-700 hover:after:w-full after:transition-all after:duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4 4 0 018 15h8a4 4 0 012.879 2.804M16 11a4 4 0 00-8 0"></path>
            </svg>
        </a>
       <!-- Projects (Updated to a new Laptop Icon) -->
      <a wire:navigate.prevent href="{{ route('projects') }}" 
      class="relative text-white font-semibold text-lg transition-all duration-300 hover:text-red-400 
      after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-red-700 hover:after:w-full after:transition-all after:duration-300">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
         <path stroke-linecap="round" stroke-linejoin="round" d="M3 17h18M5 5h14a2 2 0 012 2v8H3V7a2 2 0 012-2zm-2 12h20a2 2 0 01-2 2H3a2 2 0 01-2-2z"></path>
      </svg>
      </a>

        <!-- Contact (Updated to Email Icon) -->
        <a wire:navigate.prevent href="{{ route('contact') }}" 
           class="relative text-white font-semibold text-lg transition-all duration-300 hover:text-red-400 
           after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-red-700 hover:after:w-full after:transition-all after:duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M3 5l9 7 9-7M3 19h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </a>
   <!-- Technologies (Updated to Tools Icon - Wrench & Gear) -->
   <a wire:navigate.prevent href="{{ route('technologies') }}" 
      class="relative text-white font-semibold text-lg transition-all duration-300 hover:text-red-400 
      after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-red-700 hover:after:w-full after:transition-all after:duration-300">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
         <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m-9-9h2m14 0h2M4.93 4.93l1.41 1.41M16.66 16.66l1.41 1.41M3 12a9 9 0 1 1 9 9 9 9 0 0 1-9-9zm12-3a3 3 0 1 1-4 4l-4 4a3 3 0 1 1-4-4l4-4a3 3 0 1 1 4-4z"></path>
      </svg>
   </a>


    </nav>
</div>
