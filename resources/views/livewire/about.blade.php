<div class="relative flex flex-col lg:flex-row items-center justify-center min-h-screen bg-gray-900 text-white px-6">

    {{-- Floating Back Arrow --}}
    <a wire:navigate.prevent href="{{ route('home') }}" class="absolute top-6 left-6 bg-gray-800 text-white p-3 rounded-full shadow-md transition hover:bg-gray-700 hover:scale-110"data-aos-duration="3000"  data-aos="fade-right">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </a>

  
        <!-- Floating Profile Button Below Back Arrow -->
        <button @click="open = !open" 
            class="absolute top-20 left-6 w-16 h-16 rounded-full shadow-lg focus:outline-none transition bg-white flex items-center justify-center" data-aos-duration="3000"  data-aos="fade-right">
            <img src="/icon/man-wearing-baseball-cap-icon-600nw-530829364.webp" alt="Menu" class="w-10 h-10 rounded-full object-cover">
        </button>

     


    {{-- Profile Image --}}
    <div class="relative w-56 h-56 lg:w-72 lg:h-72 mb-8 lg:mb-0 lg:mr-10 rounded-full bg-white flex items-center justify-center shadow-lg mt-10" data-aos-duration="3000"  data-aos="fade-right">
        <img src="/icon/avatar-people-user-profile-man-boy-cap-young-512.webp" alt="Dan Fred P Cablas" 
             class="w-full h-full rounded-full object-cover transition transform duration-500 hover:scale-110 hover:shadow-2xl">
    </div>

    {{-- About Me Content --}}
    <div class="max-w-2xl space-y-6" data-aos-duration="3000"  data-aos="fade-up">
        <h2 class="text-4xl font-bold text-red-700">Hey, I'm Dan Fred P Cablas</h2>
        <p class="text-gray-300 leading-relaxed">
            I'm a Laravel developer currently studying at <strong>STI WEST NEGROS UNIVERSITY</strong>
        </p>
        <p class="text-gray-300 leading-relaxed">
            I started learning Laravel by watching tutorials and with the guidance of my teacher, who helped me understand how to become a better Laravel developer. Through hands-on practice, I improved my problem-solving skills, especially in handling logical operations and database management. 
        </p>
        <p class="text-gray-300 leading-relaxed">
            One of the most important lessons I’ve learned in Laravel is to <strong>follow best practices</strong>, such as using Eloquent relationships efficiently, structuring controllers properly, and optimizing queries for performance. Debugging and troubleshooting became easier as I worked on real-world projects, and I developed a mindset of <strong>writing clean, maintainable code</strong>. My advice to aspiring Laravel developers is to <strong>build small projects first</strong>, explore Laravel’s documentation, and never stop learning.
        </p>

        {{-- Fun Facts (Toggle with Animation) --}}
        <div x-data="{ show: false }" class="mt-4">
            <button @click="show = !show" class="bg-gray-800 px-5 py-2 rounded-md shadow-md transition hover:bg-gray-700">
                Fun Facts About Me
            </button>
            <div x-show="show" x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-y-5"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform translate-y-5"
                 class="mt-3 space-y-2 text-gray-400 bg-gray-800 p-4 rounded-md shadow-lg border-l-4 border-red-700">
                <p class="flex items-center gap-2"><span class="text-red-500 text-xl">🔥</span> I love open-source contributions.</p>
                <p class="flex items-center gap-2"><span class="text-yellow-500 text-xl">🚀</span> I build things that make life easier.</p>
                <p class="flex items-center gap-2"><span class="text-blue-500 text-xl">🎨</span> Passionate about front-end, back-end, and UI/UX development.</p>
            </div>
        </div>
    </div>

  

</div>
