@if(isset($video))
    <ul class="list-reset flex flex-wrap">
        @foreach($video->genre as $genre)
            @if(!empty($genre))
                <li class="mr-3">
                    <a class="inline-block whitespace-nowrap border rounded py-0.5 px-2 text-xs {{ $classes }}"
                       href="{{ route('video.search', ['genre' => $genre]) }}">{{ $genre }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
@endif
