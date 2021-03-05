@if(isset($video))
<ul class="list-reset flex">
    @foreach($video->genre as $genre)
        @if(!empty($genre))
            <li class="mr-3">
                <a class="inline-block border border-purple-900 text-xs md:text-sm rounded py-0.5 px-2 bg-purple-900 text-white"
                   href="{{ route('video.search', ['genre' => $genre]) }}">{{ $genre }}
                </a>
            </li>
        @endif
    @endforeach
</ul>
@endif
