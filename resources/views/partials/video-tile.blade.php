@if(isset($video))
    <div class="video-tile py-1 px-5 md:px-0">
        <a href="{{ route('video.watch', ['id' => $video->id]) }}">
            <img class="thumbnail-img"
                 src="{{ asset('/storage/images/jpeg/' . $video->metadata->thumbnail_filename) }}"
                 onmouseover="_video.preview(this)"
                 onmouseout="_video.unpreview(this)"
                 alt="Thumbnail">
            <div class="mt-2 flex justify-between">
                <p class="text-sm font-bold thumbnail-text">{{ $video->title ?? "No Title" }}</p>
                <div class="text-xs self-center ml-2">
                    <span class="text-gray-500">{{ round(rand(60, 100)) }}%</span>
                </div>
            </div>
        </a>
    </div>
@endif
