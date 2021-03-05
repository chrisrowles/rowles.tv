<div class="pt-2 bg-dark text-white {{ $classes ?? "" }}">
    <h2 class="text-lg mb-2"><i class="fas fa-list mr-2 text-purple-300"></i>{{ __('Top Games/Categories') }}</h2>
    <hr>
    <div class="text-sm mt-2">
        <ol>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['genre' => 'Skyrim']) }}">
                    Skyrim
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['genre' => 'GTA V']) }}">
                    GTA V
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['genre' => 'GTA FiveM']) }}">
                    GTA FiveM
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['genre' => 'Modding']) }}">
                    Modding
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['genre' => 'Cyberpunk 2077']) }}">
                    Cyberpunk 2077
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['genre' => 'Titanfall 2']) }}">
                    Titanfall 2
                </a>
            </li>
        </ol>
    </div>
</div>
