<div class="flex items-center justify-center min-h-screen bg-gray-900 text-white px-6">
    
    
   <!-- Floating Back Arrow -->
   <a wire:navigate.prevent href="{{ route('home') }}" class="absolute top-6 left-6 bg-gray-800 text-white p-3 rounded-full shadow-md transition hover:bg-gray-700 hover:scale-110" data-aos-duration="3000"  data-aos="fade-right">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
</a>


    <!-- Floating Profile Button Below Back Arrow -->
    <button @click="open = !open" 
        class="absolute top-20 left-6 w-16 h-16 rounded-full shadow-lg focus:outline-none transition bg-white flex items-center justify-center" data-aos-duration="3000"  data-aos="fade-right">
        <img src="/icon/man-wearing-baseball-cap-icon-600nw-530829364.webp" alt="Menu" class="w-10 h-10 rounded-full object-cover">
    </button>


        <!-- Auto-Sliding Message 
        <div x-data="{ show: true }"
            x-init="setInterval(() => { show = !show }, 5000)"
            x-show="show"
            x-transition:enter="transition transform ease-out duration-1000"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition transform ease-in duration-1000"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-10"
            class="absolute top-36 left-6 bg-gray-800 text-white text-sm px-4 py-2 rounded-md shadow-md mt-2 border-l-4 border-red-700 z-10">
            If you're done reading about me, click the back arrow to explore more!
        </div>-->


        
    <div class="bg-gray-800 p-8 rounded-lg shadow-xl w-full max-w-4xl border border-gray-700 hover:shadow-2xl transition-all duration-300 relative overflow-hidden" data-aos-duration="3000"  data-aos="fade-up">
        
        <!-- Animated Glow Effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-gray-500 blur-xl opacity-20"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10" >
            
           <!-- Left Section: Profile Info -->
<div class="flex flex-col items-center">
    <div class="relative group" x-data="{ showMessage: false }">
        <!-- Profile Picture -->
        <div 
            class="relative w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-lg hover:scale-110 transition-transform duration-300"
            @mouseenter="showMessage = true" 
            @mouseleave="showMessage = false"
        >
            <img src="/icon/avatar-people-user-profile-man-boy-cap-young-512.webp" alt="Dan Fred P Cablas" class="w-full h-full object-cover ">
        </div>

        <!-- Hover Message -->
        <div 
            x-show="showMessage"
            x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute left-25 bg-gray-400 text-black text-sm px-4 py-2 rounded-md shadow-lg"
        >
            Let's collaborate! Just contact me.
        </div>
    </div>

    <h2 class="text-2xl font-bold mt-4 text-red-700">Dan Fred P Cablas</h2>
    <p class="text-gray-400 text-center">Laravel Developer | Freelance Front-End Developer</p>
    <p class="mt-4 text-gray-400 italic text-center">
        "Let's build something amazing together. Feel free to reach out!"
    </p>
</div>

            <!-- Right Section: Contact Information -->
            <div class="space-y-6">
                <div class="flex items-center space-x-4 p-4 bg-gray-700 rounded-lg group cursor-pointer transition-transform transform hover:scale-105 hover:bg-gray-600 shadow-lg">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Gmail_icon_%282020%29.svg/2560px-Gmail_icon_%282020%29.svg.png" alt="GitHub" class="w-16 h-16 object-contain mb-4">
                    
                    <p class="text-gray-300">
                        Gmail: <a href="mailto:your-email@gmail.com" class="hover:text-red-300 transition">Cablascablas@gmail.com</a>
                    </p>
                </div>
                <div class="flex items-center space-x-4 p-4 bg-gray-700 rounded-lg group cursor-pointer transition-transform transform hover:scale-105 hover:bg-gray-600 shadow-lg">
                    <img src="/icon/github-removebg-preview.png" alt="GitHub" class="w-16 h-16 object-contain mb-4">
                    <p class="text-gray-300">
                        GitHub: <a href="https://github.com/DanExe123" target="_blank" class="hover:text-blue-300 transition">https://github.com/DanExe123</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
