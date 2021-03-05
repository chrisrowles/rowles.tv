<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-dark">
    <div class="flex self-center gap-2">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-3 px-6 bg-dark overflow-hidden">
        {{ $slot }}
    </div>
</div>
