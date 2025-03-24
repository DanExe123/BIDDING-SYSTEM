<div class="min-h-screen bg-gray-900 text-white px-6 py-12">
     {{-- Floating Back Arrow --}}
     <a wire:navigate.prevent href="{{ route('home') }}" class="absolute top-6 left-6 bg-gray-800 text-white p-3 rounded-full shadow-md transition hover:bg-gray-700 hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </a>

  
    
        <!-- Floating Profile Button Below Back Arrow -->
        <button @click="open = !open" 
            class="absolute top-20 left-6 w-16 h-16 rounded-full shadow-lg focus:outline-none transition bg-white flex items-center justify-center" data-aos-duration="3000"  data-aos="fade-right">
            <img src="/icon/man-wearing-baseball-cap-icon-600nw-530829364.webp" alt="Menu" class="w-10 h-10 rounded-full object-cover">
        </button>

        <!-- Auto-Sliding Message -->
        <div class="z-10 mb-10" data-aos-duration="3000"  data-aos="fade-right">
        <div x-data="{ show: true }"
            x-init="setInterval(() => { show = !show }, 5000)"
            x-show="show"
            x-transition:enter="transition transform ease-out duration-1000"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition transform ease-in duration-1000"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-10"
            class="absolute top-25 left-1 bg-gray-800 text-white text-sm px-4 py-2 rounded-md shadow-md mt-2 border-l-4 border-red-700 z-10">
            If you're done reading about me, click the back arrow to explore more!
        </div></div>



        
    <div class="max-w-6xl mx-auto">
        <!-- Title -->
        <h1 class="text-4xl font-bold text-center text-red-500 mb-8 animate-fade-in" data-aos-duration="3000"  data-aos="fade-down">My Projects</h1>

        <!-- Category Filters -->
        <div x-data="projects" class="text-center mb-8" data-aos-duration="3000"  data-aos="zoom-in">
            <button @click="category = 'all'" class="px-4 py-2 mx-2 bg-gray-800 rounded-md transition hover:bg-gray-700">All</button>
            <button @click="category = 'ReactNative'" class="px-4 py-2 mx-2 bg-gray-800 rounded-md transition hover:bg-gray-700">React Native</button>
            <button @click="category = 'laravel'" class="px-4 py-2 mx-2 bg-gray-800 rounded-md transition hover:bg-gray-700">Laravel</button>
            <button @click="category = 'uiux'" class="px-4 py-2 mx-2 bg-gray-800 rounded-md transition hover:bg-gray-700">UI/UX</button>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                <template x-for="project in projects" :key="project.id">
                    <div 
                        x-show="category === 'all' || category === project.category"
                        x-transition:enter="transition ease-out duration-500 transform"
                        x-transition:enter-start="opacity-0 translate-y-10"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="relative bg-gray-800 p-4 rounded-lg shadow-lg transition transform hover:scale-105 hover:shadow-2xl">
                        
                        <!-- Project Image -->
                        <img :src="project.image" alt="" class="w-full h-40 object-cover rounded-md" >

                        <!-- Project Info -->
                        <h3 class="text-xl font-semibold mt-4" x-text="project.name"></h3>
                        <p class="text-gray-400 text-sm" x-text="project.description"></p>

                       
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js Data -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('projects', () => ({
            category: 'all',
            projects: [
                { id: 1, name: 'POS and Inventory', category: 'ReactNative', description: 'A modern Point of sale and Inventory system using ReactNative.', image: '/icon/Capture.PNG', link: '#' },
                { id: 2, name: 'Chrome Hearts Shop', category: 'laravel', description: 'A Chromehearts Shop using T.A.L.L. Stack', image: '/icon/chomehearts.PNG', link: '#' },
                { id: 3, name: 'E-commerce System, ', category: 'laravel', description: 'A E-commerce using Laravel Filament.', image: '/icon/ecommerce.PNG', link: '#' },
                { id: 4, name: 'Digital Vet System', category: 'laravel', description: 'A Digital Vet clinic system with Appointment Scheduling and Data Analytics , Email Alert , RealTimeChat System.', image: '/icon/abouts.png', link: '#' },
                { id: 5, name: 'ThriftShopBCD', category: 'laravel', description: 'A simple THRIFTSHOPBCD using DaisyUi Tailwind Laravel.', image: '/icon/bplks.jpg', link: '#' },
                { 
                id: 6, 
                name: 'FinTrack: Smart Expense Management System', 
                category: 'laravel', 
                description: 'A Laravel and Ajax. (client project)', 
                image: '/icon/unavailable-rubber-grunge-stamp-seal-illustration-vector.jpg', // Placeholder for unavailable image
                link: '#' 
            },
            { 
                id: 7, 
                name: 'Digital Complaint Filing System', 
                category: 'laravel', 
                description: 'A digital complaint filing using jQuery, Ajax, TailwindCSS, Laravel (client project).', 
                image: '/icon/unavailable-rubber-grunge-stamp-seal-illustration-vector.jpg', // Placeholder for unavailable image
                link: '#' 
            },

           
            ],
        }));
    });
</script>
