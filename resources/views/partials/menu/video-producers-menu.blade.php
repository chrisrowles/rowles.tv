<div class="pt-2 {{ $classes ?? "" }}">
    <h2 class="text-lg mb-2">
        <i class="fas fa-star mr-2 text-purple-400"></i>{{ __('Top Producers') }}
    </h2>
    <hr>
    <div class="text-sm mt-2">
        <ol>
            @foreach($producers as $streamer)
                <li>
                    <a class="hover:text-purple-300" href="{{ route('video.search', ['producer' => $streamer]) }}">
                        {{ __(ucfirst($streamer)) }}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
</div>
