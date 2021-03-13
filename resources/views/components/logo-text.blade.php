<a href="{{ route('video.index') }}" class="text-2xl self-center font-extrabold -letter-space-5 logo-text">
    @if(request()->routeIs('dashboard'))
        <span class="text-white font-light">| administration</span>
    @endif
</a>
