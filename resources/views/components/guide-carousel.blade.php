<div 
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-500"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-400"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
>
    <div class="relative w-[95%] md:w-[80%] lg:w-[70%] bg-white rounded-3xl shadow-2xl p-6 md:p-10 overflow-hidden">
        
        <!-- Close Button -->
        <button 
            @click="closeGuide()" 
            class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition"
        >
            <x-phosphor.icons::regular.x class="w-6 h-6" />
        </button>

        <!-- Slides -->
        <div class="relative h-[520px] flex items-center justify-center">
            <template x-for="(slide, index) in slides" :key="index">
                <div
                    x-show="current === index"
                    x-transition:enter="transition transform ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition transform ease-in duration-400"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-10"
                    class="absolute inset-0 flex flex-col items-center justify-center space-y-6 px-4"
                >
                    <div class="mockup-window bg-base-100 border border-gray-200 rounded-2xl overflow-hidden w-full max-w-6xl mx-auto shadow-lg">
                        <img :src="slide.image" class="w-full h-[380px] md:h-[520px] object-cover" />
                    </div>

                    <div class="text-center max-w-3xl">
                        <h2 class="text-2xl md:text-3xl font-bold text-[#062B4A]" x-text="slide.title"></h2>
                        <p class="text-gray-600 mt-3 text-base md:text-lg leading-relaxed" x-text="slide.description"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center mt-8">
            <button 
                @click="prev()" 
                class="btn btn-outline btn-sm rounded-md py-3 px-6 bg-[#062B4A] text-white hover:bg-[#094068] transition-all duration-300"
            >
                Previous
            </button>

            <div class="flex gap-2">
                <template x-for="(slide, i) in slides" :key="i">
                    <div 
                        class="w-3 h-3 rounded-full transition-all duration-300"
                        :class="current === i ? 'bg-[#062B4A] scale-125' : 'bg-gray-300'"
                    ></div>
                </template>
            </div>

            <button 
                @click="next()" 
                x-text="current === slides.length - 1 ? 'Finish' : 'Next'" 
                class="btn btn-primary btn-sm rounded-md py-3 px-6 bg-[#062B4A] text-white hover:bg-[#094068] transition-all duration-300"
            ></button>
        </div>
    </div>
</div>
