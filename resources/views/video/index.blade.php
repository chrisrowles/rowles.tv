<x-app-layout>
    <div class="hidden md:block hero-image relative">
        <div class="overlay bg-dark opacity-70"></div>
    </div>
    <div class="w-full bg-dark py-0.5 shadow"></div>
    <div class="pt-0 md:pt-3 pb-6 bg-dark">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="hidden md:block col-span-2">
                    @include('partials.menu.video-stars-menu')
                    @include('partials.menu.video-categories-menu', ['classes' => 'mt-5'])
                </div>
                <div class="col-span-12 md:col-span-10">
                    <div class="mb-5">
                        <div class="pt-2 bg-dark text-white px-5 md:px-0">
                            <h2 class="text-xl mb-2">{{ __('Search Videos') }}</h2>
                            @include('partials.form.video-search-form', [
                                'action' => route('video.search'),
                                'theme' => 'bg-dark text-gray-300',
                                'button' => 'bg-purple-600 hover:bg-purple-500'
                            ])
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-2 text-gray-200">
                        @if($videos->total() > 0)
                            @foreach($videos as $video)
                                @include('partials.video-tile')
                            @endforeach
                        @else
                            <span class="text-xl font-light">{{ __('There is nothing here.') }}</span>
                        @endif
                    </div>
                    <div class="mt-6">
                        {{ $videos->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
