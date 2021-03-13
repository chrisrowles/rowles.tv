@if(isset($video))
    <div class="flex flex-col video-tile py-1 px-5 md:px-0">
        <a href="{{ route('video.watch', ['id' => $video->id]) }}">
            <div class="preview" onmouseenter="_video.preview(this)" onmouseleave="_video.unpreview(this)">
                <img src="{{  config('app.cloudasset.image') . '/jpeg/' . $video->metadata->thumbnail_filename }}"
                     alt="Thumbnail" class="media">
            </div>
            <div class="mt-2 flex justify-between">
                <p class="font-bold thumbnail-text">{{ $video->title ?? "No Title" }}</p>
                <div class="text-xs self-center ml-2">
                    <span class="text-gray-500">{{ round(rand(60, 100)) }}%</span>
                </div>
            </div>
            <div class="mt-1">
                @include('partials.video-genre-pills', ['class' => 'border-gray-500 bg-gray-500 text-white'])
            </div>
        </a>
    </div>
@endif
