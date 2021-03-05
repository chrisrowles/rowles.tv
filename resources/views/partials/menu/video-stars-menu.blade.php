<div class="pt-2 {{ $classes ?? "" }}">
    <h2 class="text-lg mb-2">
        <i class="fas fa-star mr-2 text-yellow-400"></i> {{ __('Top Streamers') }}
    </h2>
    <hr>
    <div class="text-sm mt-2">
        <ol>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['title' => '']) }}">
                    Streamer #1
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['title' => '']) }}">
                    Streamer #2
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['title' => '']) }}">
                    Streamer #3
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['title' => '']) }}">
                    Streamer #4
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['title' => '']) }}">
                    Streamer #5
                </a>
            </li>
            <li>
                <a class="hover:text-purple-300" href="{{ route('video.search', ['title' => '']) }}">
                    Streamer #6
                </a>
            </li>
        </ol>
    </div>
</div>
