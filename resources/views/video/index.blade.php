<x-app-layout>
    <div class="hidden md:block hero-image relative">
        <div class="overlay bg-dark opacity-70"></div>
    </div>
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('home') }}
    </div>
    <div class="pt-0 md:pt-3 pb-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="hidden md:block col-span-2">
                    @include('partials.menu.video-stars-menu')
                    @include('partials.menu.video-categories-menu', ['classes' => 'mt-5'])
                    <div class="mt-10">
                        <div class="card bg-white sm:rounded-lg border shadow">
                            <div class="card-inner bg-white shadow-inner">
                                <h2 class="text-2xl font-bold">
                                    Join the
                                    <span class="flex flex-nowrap">@include('components.logo')</span>
                                    Community Today!
                                </h2>
                                <button class="default-button text-white bg-yellow-600 hover:bg-yellow-500 mt-3 py-3 sm:rounded-lg">
                                    Subscribe Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-10">
                    <div class="mb-5">
                        <div class="pt-2 px-5 md:px-0">
                            <h2 class="text-xl mb-2">{{ __('Search Videos') }}
                                <span class="text-gray-400 text-xs">(We have {{ $videos->total() }} in total!)</span>
                            </h2>
                            @include('partials.form.video-search-form', [
                                'action' => route('video.search'),
                                'theme' => 'bg-white text-gray-600',
                                'button' => 'bg-yellow-600 hover:bg-yellow-500 text-white'
                            ])
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-2">
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
