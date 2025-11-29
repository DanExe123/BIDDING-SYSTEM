<!DOCTYPE html>
<html lang="en" x-data="{ mobileMenuOpen: false }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KALINAW</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-white font-sans">

    <!-- Top Blue Bar -->
    <div class="bg-[#1540D2] text-white text-sm py-10 relative">
        <div class="max-w-screen-lg mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="icon/bagologo.png" alt="Logo" class="w-6 h-6 md:w-8 md:h-8" />
                <div>
                    <p class="font-bold text-sm md:text-base">KALINAW</p>
                </div>
            </div>
            <div class="flex items-center gap-4" x-data="clock()" x-init="start()">
                <span x-text="time" class="hidden sm:inline"></span>
            </div>

        </div>
    </div>

   <!-- Navbar Overlay -->
   <nav class="absolute top-[80px] left-1/2 transform -translate-x-1/2 z-50 w-[80%] max-w-screen-lg">
    <ul class="flex justify-between items-center text-sm font-medium text-black bg-white shadow-md px-6 rounded-md py-4 w-full">
        <!-- Left group -->
        <div class="flex gap-6">
            <li><a href="#home" class="hover:text-blue-600">Home</a></li>
            <li><a href="#information" class="hover:text-blue-600">Information</a></li>
        </div>
    
        <!-- Right group -->
        <div class="flex gap-6 items-center">
            @guest
                <li>
                    <a href="{{ route('register') }}" class="hover:text-blue-600">Register</a>
                </li>
                <li>
                    <a href="{{ route('login') }}" 
                       class="group border border-blue-600 px-3 py-1 rounded-md relative z-20 text-blue-600 hover:text-white hover:bg-blue-600 transition">
                        Login
                    </a>
                </li>
            @else
                @php $user = Auth::user(); @endphp
        
                <li>
                    @if($user->hasRole('Super_Admin'))
                        <a href="{{ route('superadmin-dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    @elseif($user->hasRole('BAC_Secretary'))
                        <a href="{{ route('bac-dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    @elseif($user->hasRole('Supplier'))
                        <a href="{{ route('supplier-dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    @elseif($user->hasRole('Purchaser'))
                        <a href="{{ route('purchaser-dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    @else
                        <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                    @endif
                </li>
        
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="text-blue-600 border border-blue-600 px-3 py-1 rounded-md hover:bg-blue-600 hover:text-white transition">
                            Logout
                        </button>
                    </form>
                </li>
            @endguest
        </div>
        
    </ul>
    
    </nav>

            <!-- Hero Section -->
            <section id="home" 
            x-data="{
                current: 0,
                images: [
                    '/icon/bago123.jpg',
                    '/icon/welcome2.jpg'
                ]
            }" 
            x-init="setInterval(() => { current = (current + 1) % images.length }, 3000)"
            class="relative h-[700px] overflow-hidden">

            <!-- Background Images -->
            <template x-for="(image, index) in images" :key="index">
                <div x-show="current === index" 
                    x-transition:enter="transition-opacity ease-in-out duration-1000"
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in-out duration-1000" 
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" 
                    class="absolute inset-0 w-full h-full bg-cover bg-center"
                    :style="`background-image: url('${image}'); background-size: cover; background-position: center;`">
                </div>
            </template>
             <!-- Overlay -->
             <div class="absolute inset-0 bg-gradient-to-b from-transparent to-blue-500/70"></div>
                <div class="relative z-10 flex flex-col justify-center items-start max-w-screen-xl mx-auto h-full px-6">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-white uppercase">BAGO CITY</h1>
                    <p class="text-lg md:text-xl text-gray-200 mt-2">Home of Historical and Natural Treasures</p>
                </div>
            </section>

    <!-- Floating Cards Section -->
    <section id="information" class="relative z-20 -mt-20 mb-10">
        <div class="max-w-screen-xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 px-6">
            
            <!-- Card 1 -->
            <div class="h-56 bg-white shadow-lg rounded-lg p-6 transform transition duration-500 ease-in-out hover:-translate-y-2 hover:shadow-2xl">
                <x-phosphor.icons::regular.rocket-launch class="w-8 h-8 text-blue-600 mb-3" />
                <h3 class="text-lg font-bold text-gray-800 mb-2">Faster Requests</h3>
                <p class="text-gray-600 text-sm">
                    Purchase requests move quickly from submission to approval with less paperwork.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="h-56 bg-white shadow-lg rounded-lg p-6 transform transition duration-500 ease-in-out hover:-translate-y-2 hover:shadow-2xl">
                <x-phosphor.icons::regular.handshake class="w-8 h-8 text-green-600 mb-3" />
                <h3 class="text-lg font-bold text-gray-800 mb-2">Fair Supplier Selection</h3>
                <p class="text-gray-600 text-sm">
                    The system uses decision support tools to help choose the best supplier based on price and quality.
                </p>
            </div>

            <!-- Card 4 -->
            <div class="h-56 bg-white shadow-lg rounded-lg p-6 transform transition duration-500 ease-in-out hover:-translate-y-2 hover:shadow-2xl">
                <x-phosphor.icons::regular.chart-bar class="w-8 h-8 text-purple-600 mb-3" />
                <h3 class="text-lg font-bold text-gray-800 mb-2">Clear Monitoring</h3>
                <p class="text-gray-600 text-sm">
                    Track purchase requests and awards easily through the system.
                </p>
            </div>

        </div>
    </section>



    <!-- Footer -->
    <footer class="bg-[#0F0C2D] py-6 px-4">
        <div class="max-w-screen-xl mx-auto flex items-center gap-3 text-white">
            <img src="icon/bagologo.png" alt="City Seal" class="h-12 sm:h-14 md:h-16" />
            <p class="text-xs sm:text-sm">© City Government of Bago</p>
        </div>
    </footer>
    <script>
        function clock() {
            return {
                time: '',
                start() {
                    this.update()
                    setInterval(() => this.update(), 1000)
                },
                update() {
                    const options = { 
                        timeZone: "Asia/Manila", 
                        hour: "2-digit", 
                        minute: "2-digit", 
                        hour12: true 
                    }
                    const formatter = new Intl.DateTimeFormat("en-US", options)
                    this.time = `GMT+8 • ${formatter.format(new Date())}`
                }
            }
        }

        // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    </script>
</body>
</html>
