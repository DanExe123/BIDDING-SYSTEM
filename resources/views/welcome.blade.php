<!DOCTYPE html>
<html lang="en" x-data="{ mobileMenuOpen: false }" x-init="$watch('mobileMenuOpen', value => console.log('Mobile menu:', value))">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KALINAW</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-white font-sans">

    <!-- Navbar -->
    <nav class="bg-[#04233D] text-white px-4 py-3 shadow" x-data="{ mobileMenuOpen: false }">
        <div class="flex items-center justify-between">
            <!-- Logo and Name -->
            <div class="flex items-center space-x-2">
                <img src="icon/bagologo.png" alt="Logo" class="w-8 h-8 md:w-10 md:h-10 rounded-full" />
                <span class="text-base md:text-lg font-bold">KALINAWKITA</span>
            </div>

            <!-- Hamburger (Mobile) -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden text-white text-2xl focus:outline-none">
                ☰
            </button>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex justify-between items-center w-full">
                <!-- Centered Menu Links -->
                <ul class="flex-1 flex justify-center gap-6 text-xs sm:text-sm md:text-xs lg:text-base">
                    <li><a href="#" class="hover:text-yellow-400 px-4">Announcement</a></li>
                    <li><a href="#" class="hover:text-yellow-400 px-4">Invitation Bids</a></li>
                    <li><a href="#" class="hover:text-yellow-400 px-4">Awarded Bidders</a></li>
                </ul>


                <!-- Right-Aligned Login -->
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('register') }}" class="hover:text-yellow-400">Register</a>

                        <a href="{{ route('login') }}" class="group border border-white px-3 py-1 rounded-md relative z-20">
                            <span class="flex items-center gap-2 text-white group-hover:text-yellow-400">Login</span>
                        </a>
                    @else
                        @php $user = Auth::user(); @endphp

                        @if($user->hasRole('Super_Admin'))
                            <a href="{{ route('superadmin-dashboard') }}" class="group border border-white px-3 py-1 rounded-md relative z-20">Dashboard</a>
                        @elseif($user->hasRole('BAC_Secretary'))
                            <a href="{{ route('bac-dashboard') }}" class="group border border-white px-3 py-1 rounded-md relative z-20">Dashboard</a>
                        @elseif($user->hasRole('Supplier'))
                            <a href="{{ route('supplier-dashboard') }}" class="group border border-white px-3 py-1 rounded-md relative z-20">Dashboard</a>
                        @elseif($user->hasRole('Purchaser'))
                            <a href="{{ route('purchaser-dashboard') }}" class="group border border-white px-3 py-1 rounded-md relative z-20">Dashboard</a>
                        @else
                            <a href="{{ route('home') }}" class="group border border-white px-3 py-1 rounded-md relative z-20">Dashboard</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="text-white group-hover:text-yellow-400 border border-white px-3 py-1 rounded-md">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>

            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="sm:hidden mt-3 space-y-2 text-sm px-2">
                <a href="#" class="block hover:text-yellow-400">Announcement</a>
                <a href="#" class="block hover:text-yellow-400">Invitation Bids</a>
                <a href="#" class="block hover:text-yellow-400">Awarded Bidders</a>
                <a href="#" class="block hover:text-yellow-400">Register</a>
                <a href="#" class="block hover:text-yellow-400 border px-3 py-1 rounded-md w-fit">Login</a>
            </div>
    </nav>

    <!-- Hero Section -->
    <section
        class="w-full h-64 sm:h-72 md:h-96 lg:h-[32rem] bg-blue-500 flex items-center justify-center overflow-hidden">
        <img src="icon/bg-1.png" alt="Header Graphic" class="w-full h-auto object-contain mt-0 lg:mt-[212px]" />
    </section>


    <!-- Container Section (relative for absolute children positioning) -->
    <section class="relative w-full">
        <!-- Top Background -->
        <div class="w-full bg-[#476575] h-32 sm:h-40 md:h-48 lg:h-56"></div>

        <!-- Bottom Background -->
        <div class="w-full bg-[#648DA5] h-[200px]"></div>

        <!-- Card Container (absolutely centered on split line) -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full px-6">
            <div class="max-w-screen-xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="h-32 md:h-40 bg-white shadow-md rounded"></div>
                <div class="h-32 md:h-40 bg-white shadow-md rounded"></div>
                <div class="h-32 md:h-40 bg-white shadow-md rounded"></div>
            </div>
        </div>
    </section>




    <!-- Footer -->
    <footer class="bg-[#0F0C2D] py-6 px-2 flex justify-start">
        <div class="flex justify-start">
            <img src="icon/bagologo.png" alt="City Seal" class="h-12 sm:h-14 md:h-16" />
        </div>
    </footer>

</body>

</html>
