
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
        <div class="cloud absolute top-5 left-1/4 w-32 h-20 bg-white opacity-70 rounded-full blur-lg animate-cloud-move"></div>
        <div class="cloud absolute top-20 left-1/2 w-40 h-24 bg-white opacity-60 rounded-full blur-lg animate-cloud-move"></div>
        <div class="cloud absolute top-30 left-3/4 w-36 h-22 bg-white opacity-50 rounded-full blur-lg animate-cloud-move"></div>  
    </div>
    <div class="space-y-6" data-aos-duration="3000"  data-aos="fade-up">
 

    <!-- style clouds --> 
    <style>
        @keyframes cloud-move {
            0% { transform: translateX(-100px); opacity: 0.5; }
            50% { opacity: 0.8; }
            100% { transform: translateX(100vw); opacity: 0.5; }
        }
    
        .animate-cloud-move {
            animation: cloud-move 20s linear infinite;
        }
    </style>


    <!-- content -->
    <h1 class="text-6xl font-extrabold">
        <i class="fa-solid fa-laptop-code text-blue-400"></i> 
        Hello, I'm Dan <br> 
        a <span class="text-red-600">Laravel Developer</span>
    </h1>
    
    <p class="text-2xl text-gray-300">
        <i class="fa-solid fa-code text-cyan-400"></i> Passionate about building modern & scalable web applications.
    </p>
    </div>


    <div x-data="{ open: false, showMessage: true }" class="fixed top-1/2 left-6 transform -translate-y-1/2" data-aos-duration="3000"  data-aos="fade-right">
        <!-- Message with Smooth Transition -->
        <div wire:ignore 
        x-show="showMessage && !open" 
        x-transition.opacity.duration.500ms
        class="absolute mb-2 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-gray-900 text-white text-sm px-6 py-3 rounded-2xl shadow-lg flex items-center space-x-2 relative bubble-animation">

    <span class=" text-white  font-bold" >Hey! Click me!</span>

    <!-- Chat Bubble Tail -->
    <div class="absolute -bottom-2 right-1/2 -translate-x-1/2 w-4 h-4 bg-gray-900 rotate-45"></div>
    </div>

    <style>
    /* Soft Glow Effect */
    .bubble-animation {
        animation: glow 2s infinite alternate;
    }

    @keyframes glow {
        0% { box-shadow: 0px 0px 8px rgba(0, 172, 255, 0.5); }
        100% { box-shadow: 0px 0px 16px rgba(0, 172, 255, 0.8); }
    }
    </style>

    <div class="mb-10">
        <button @click="open = !open" 
        class="w-16 h-16 rounded-full shadow-lg focus:outline-none transition bg-white flex items-center justify-center">
      <img src="/icon/man-wearing-baseball-cap-icon-600nw-530829364.webp" alt="Menu" class="w-10 h-10 rounded-full object-cover">
      </button></div


      