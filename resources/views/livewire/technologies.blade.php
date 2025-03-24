<div x-data="cursorEffect()" class="min-h-screen bg-gray-900 text-white px-6 py-12 relative overflow-hidden">
    
    <!-- Candle-Like Sparkling Cursor -->
    <template x-for="particle in particles" :key="particle.id">
        <div class="absolute w-4 h-4 bg-yellow-400 rounded-full opacity-75 pointer-events-none animate-flicker"
             :style="{ left: `${particle.x}px`, top: `${particle.y}px`, transform: 'translate(-50%, -50%)' }">
        </div>
    </template>


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
            class="absolute top-36 left-6 bg-gray-800 text-white text-sm px-4 py-2 rounded-md shadow-md mt-2 border-l-4 border-red-700"data-aos-duration="3000"  data-aos="fade-right">
            If you're done reading about me, click the back arrow to explore more!
        </div>-->

<!-- Technologies Section -->
<div class="max-w-6xl mx-auto mt-5 relative" x-data="techStack()" data-aos-duration="3000"  data-aos="fade-down">
    <h1 class="text-4xl font-bold text-center text-red-500 mb-8 animate-fade-in">Technologies I Use</h1>
    <p class="text-center text-gray-400 mb-10">
        These are the technologies I used to build modern web applications.
    </p>

    <!-- Explosion Background (Hidden Initially) -->
    <div x-show="explode !== null"
         class="fixed inset-0 bg-red-600 opacity-0 z-0 explosion-bg"
         x-transition.opacity.duration.500ms>
    </div>

    <!-- Technologies Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 relative z-10" data-aos-duration="3000"  data-aos="fade-up">
        <template x-for="(tech, index) in techs" :key="tech.name">
            <div x-show="!removed.includes(index)"
                 @mouseenter="startBombTimer(index)" 
                 @mouseleave="stopBombTimer"
                 class="bg-gray-800 p-4 rounded-lg shadow-lg flex flex-col items-center relative transition-all duration-500">

                <!-- Bomb Countdown Timer -->
                <div x-show="isHovering === index"
                     class="absolute top-2 left-2 w-10 h-10 bg-black text-white flex items-center justify-center rounded-full text-xs font-bold">
                    💣 <span x-text="bombCountdown"></span>s
                </div>

                <!-- Explosion Effect (Behind Content) -->
                <div x-show="explode === index"
                     class="absolute inset-0 bg-red-600 opacity-80 rounded-full animate-explode">
                </div>

                <img :src="tech.image" :alt="tech.name" class="w-16 h-16 object-contain mb-4">
                <p class="text-center" x-text="tech.name"></p>
            </div>
        </template>
    </div>

    <script>
    function techStack() {
        return {
            techs: [
                { name: "Composer", image: "/icon/composer-removebg-preview.png" },
                { name: "VSCode", image: "/icon/vscode-removebg-preview.png" },
                { name: "XAMPP", image: "/icon/xamp-removebg-preview.png" },
                { name: "Command Prompt", image: "/icon/cmd-removebg-preview.png" },
                { name: "Git", image: "/icon/git-removebg-preview.png" },
                { name: "GitHub", image: "/icon/github-removebg-preview.png" },
                { name: "Postman", image: "/icon/postman-removebg-preview.png" },
                { name: "Laravel", image: "/icon/laravel-removebg-preview.png" },
                { name: "TailwindCSS", image: "/icon/tailwind-removebg-preview.png" },
                { name: "DaisyUI", image: "/icon/daisyui-removebg-preview.png" },
                { name: "AOS Animation Scroll", image: "/icon/aos-removebg-preview.png" },
                { name: "React Native", image: "/icon/reactnative-removebg-preview.png" },
                { name: "Livewire", image: "/icon/livewire-removebg-preview.png" },
                { name: "AlpineJS", image: "/icon/alpinejs-removebg-preview.png" },
                { name: "PHP", image: "/icon/php-removebg-preview.png" }
            ],
            isHovering: null,
            bombCountdown: 10,
            bombTimer: null,
            explode: null,
            removed: [],

            startBombTimer(index) {
                this.isHovering = index;
                this.bombCountdown = 10;
                this.explode = null;
                clearInterval(this.bombTimer);

                this.bombTimer = setInterval(() => {
                    this.bombCountdown--;
                    if (this.bombCountdown <= 0) {
                        clearInterval(this.bombTimer);
                        this.explode = index;

                        setTimeout(() => {
                            this.removed.push(index);
                            this.explode = null;
                        }, 1000);
                    }
                }, 1000);
            },

            stopBombTimer() {
                clearInterval(this.bombTimer);
                this.isHovering = null;
                this.bombCountdown = 10;
            }
        };
    }
    </script>

    <style>
        /* Explosion Background Animation */
        .explosion-bg {
            animation: redExplosion 0.5s ease-in-out forwards;
        }

        /* Red Explosion Animation */
        .animate-explode {
            animation: explodeEffect 0.5s ease-in-out forwards;
        }

        @keyframes redExplosion {
            from { opacity: 0; }
            to { opacity: 0.8; }
        }

        @keyframes explodeEffect {
            0% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(2); opacity: 0.8; }
            100% { transform: scale(3); opacity: 0; }
        }
    </style>
</div>
