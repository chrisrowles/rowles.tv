@extends('layouts.app')

@section('content')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('dashboard') }}
    </div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl">{{ __('Video Management') }}</h1>
    </div>
    <div class="pb-6">
        <div class="max-w-full mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 items-start md:gap-10" x-data="xVideo()">
                <div class="h-full md:border-r md:border-gray-300 md:pr-10">
                    <div class="gap-2">
                        <div class="flex gap-2">
                            @include('partials.video-select-dropdown')
                        </div>
                        <div class="pt-6 items-center gap-2">
                            @include('partials.form.video-search-form', [
                                'action' => route('admin.dashboard'),
                                'theme' => 'bg-white text-black',
                                'button' => 'primary-button'
                            ])
                        </div>
                        <div class="pt-3 hidden md:block">
                            @include('partials.video-list-table')
                        </div>
                        <div class="mt-6 hidden md:block">
                            {{ $videos->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-0">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        @include('partials.form.video-details-form')
                        <div class="pt-7">
                            <img class="w-full hidden" src=""
                                 alt="Thumbnail"
                                 id="video-thumbnail">
                            @include('partials.video-attributes-table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('partials.refs.video-x-ref')
@endsection

@section('footer')
@endsection

