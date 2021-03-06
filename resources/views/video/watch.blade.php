<x-app-layout>
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('watch', $video) }}
    </div>
    <div class="pt-3 pb-6">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 md:px-0">
                <h1 class="text-4xl mb-4">{{ $video->title }}</h1>
                @include('partials.video-genre-pills', ['classes' => 'border-purple-900 bg-purple-900 text-white md:text-sm'])
            </div>
            <div class="pt-6">
                @include('partials.video-player', ['file' => config('app.cloudasset.video') . '/' . $video->filename])
            </div>
            <div class="pt-6">
                <h2 class="text-3xl mb-4 px-5 md:px-0">{{ __('Related Videos') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-2">
                    @foreach($related as $item)
                        @include('partials.video-tile', ['video' => $item])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
