@extends('layouts.app')

@section('content')
    <x-hero-image class="max-h-72" />
    <x-breadcrumb link="admin.video">
        <h1 class="text-3xl mt-1">{{ __('Video Management') }}</h1>
    </x-breadcrumb>
    <div class="mt-8 pb-6">
        <div class="max-w-full mx-auto px-6 lg:px-8">
            <div class="flex flex-col items-start" x-data="xVideo()">
                <div class="w-full">
                    <div class="gap-2">
                        <div class="flex gap-2">
                            @include('partials.video-select-dropdown')
                        </div>
                        <div class="pt-6 gap-2 hidden sm:flex sm:flex-col">
                            @include('partials.form.video-search-form', [
                                'action' => route('admin.video'),
                                'theme' => 'bg-white text-black',
                                'button' => 'primary-button'
                            ])
                        </div>
                        <div class="pt-3 hidden sm:block overflow-x-auto">
                            @include('partials.video-list-table')
                        </div>
                        <div class="mt-6 hidden sm:block">
                            {{ $videos->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
                <x-slideover gap="16">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 md:gap-10">
                        @include('partials.form.video-details-form')
                        <div class="pt-7">
                            <img class="w-full hidden" src=""
                                 alt="Thumbnail"
                                 id="video-thumbnail">
                            @include('partials.video-attributes-table')
                        </div>
                    </div>
                </x-slideover>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('partials.refs.video-x-ref')
@endsection

@section('footer')
@endsection

