<a href="{{ route('video.index') }}" class="text-2xl self-center font-extrabold -letter-space-5">
    <span class="text-yellow-700">L</span>
    <span class="text-yellow-600">O</span>
    <span class="text-yellow-500">V</span>
    <span class="text-yellow-400">E</span>
    <span class="text-yellow-300">P</span>
    <span class="text-yellow-400">O</span>
    <span class="text-yellow-500">O</span>
    @if(request()->routeIs('dashboard'))
        <span class="text-white font-light">| administration</span>
    @endif
</a>
