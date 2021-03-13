<div x-init="() => { showSlideover = false }" class="pt-{{ $gap }} fixed inset-0 overflow-hidden"
     :class="showSlideover ? 'z-1' : '-z-1'">
    <div class="absolute pt-{{ $gap }} inset-0 overflow-hidden">
        <div x-show="showSlideover" @click="showSlideover = false" @anim('tailwindui.slideover.overlay')
        class="absolute pt-{{ $gap }} inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <section class="absolute pt-{{ $gap }} inset-y-0 right-0 md:pl-10 max-w-full flex">
            <div x-show="showSlideover" @anim('tailwindui.slideover.panel') class="w-screen md:max-w-5xl">
                <div class="h-full flex flex-col space-y-3 py-3 bg-white shadow-xl overflow-y-scroll">
                    <header class="px-4 sm:px-6">
                        <div class="flex items-start justify-between space-x-3">
                            <div class="h-7 flex items-center">
                                <button @click="showSlideover = false" aria-label="Close panel"
                                        class="text-gray-400 hover:text-gray-500 transition ease-in-out duration-150">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="relative flex-1 px-4 sm:px-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
