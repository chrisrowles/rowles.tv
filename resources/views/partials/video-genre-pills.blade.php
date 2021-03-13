@if(isset($video))
    <ul class="list-reset flex flex-wrap">
        @foreach($video->genre as $genre)
            @if(!empty($genre))
                <li class="mr-3">
                    <x-pill-link class="{{ $class ?? '' }}" href="{{ route('video.search', ['genre' => $genre]) }}">
                        {{ $genre }}
                    </x-pill-link>
                </li>
            @endif
        @endforeach
    </ul>
@endif
