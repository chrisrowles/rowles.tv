<x-app-layout>
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('dashboard') }}
    </div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl">{{ __('Video Management') }}</h1>
    </div>
    <div class="pb-6">
        <div class="max-w-full mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 items-start md:gap-10" x-data="select()">
                <div class="h-full md:border-r md:border-gray-300 md:pr-10">
                    <div class="gap-2">
                        <div class="flex gap-2">
                            @include('partials.video-select-dropdown')
                        </div>
                        <div class="pt-3 items-center gap-2 hidden md:flex">
                            @include('partials.form.video-search-form', [
                                'action' => route('dashboard'),
                                'theme' => 'bg-white text-black',
                                'button' => 'primary-button'
                            ])
                        </div>
                        <div class="hidden md:block">
                            @include('partials.video-list-table')
                        </div>
                        <div class="mt-6 hidden md:block">
                            {{ $videos->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @include('partials.form.video-details-form')
                        <div class="pt-7">
                            <img class="w-full" src=""
                                 onmouseover="_video.preview(this)"
                                 onmouseout="_video.unpreview(this)"
                                 alt="Thumbnail"
                                 id="video-thumbnail">
                            @include('partials.video-attributes-table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@include('partials.video-x-select')
