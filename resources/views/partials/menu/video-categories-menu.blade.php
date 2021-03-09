<div class="pt-2 {{ $class ?? "" }}">
    <h2 class="text-lg mb-2">
        <i class="fas fa-list mr-2 text-purple-300"></i>{{ __('Top Categories') }}</h2>
    <hr>
    <div class="text-sm mt-2">
        @if(isset($categories))
            <ol>
                @foreach($categories as $genre)
                    <li>
                        <a class="hover:text-purple-300" href="{{ route('video.search', ['genre' => $genre]) }}">
                            {{ __(ucfirst($genre)) }}
                        </a>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
