<div class="{{ $class ?? "" }}">
    <h2 class="text-lg mb-2">
        <i class="fas fa-star mr-2 text-purple-400"></i>{{ __('Top Producers') }}
    </h2>
    <hr>
    <div class="text-sm mt-2">
        @if(isset($producers))
            <ol>
                @foreach($producers as $streamer)
                    <li>
                        <a class="hover:text-purple-300" href="{{ route('video.search', ['producer' => $streamer]) }}">
                            {{ __(ucfirst($streamer)) }}
                        </a>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
