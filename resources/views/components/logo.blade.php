<a href="{{ route('video.index') }}" class="text-2xl self-center font-extrabold -letter-space-5">
    <span class="text-purple-700">D</span>
    <span class="text-purple-600">A</span>
    <span class="text-purple-500">N</span>
    <span class="text-purple-400">I</span>
    <span class="text-purple-300">.</span>
    <span class="text-purple-400">T</span>
    <span class="text-purple-500">V</span>
    @if(request()->routeIs('dashboard'))
        <span class="text-white font-light">| administration</span>
    @endif
</a>
