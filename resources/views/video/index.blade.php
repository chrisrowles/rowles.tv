@extends('layouts.app')

@section('content')
    <x-hero-image class="max-h-72" />
    <x-breadcrumb link="home" />
    <div class="max-w-full content-container pt-0 md:pt-3 pb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Sidebar -->
            <div class="hidden md:block col-span-2">
                @include('partials.menu.video-producers-menu', ['class' => 'mt-2'])
                @include('partials.menu.video-categories-menu', ['class' => 'mt-6'])
                <div class="mt-8"><x-subscribe-card /></div>
            </div>
            <!-- Main content -->
            <div class="col-span-12 md:col-span-10">
                <div class="mt-2 mb-5 px-5 md:px-0">
                    <h2 class="text-xl mb-2">{{ __('Search Videos') }}
                        <span class="text-gray-400 text-xs">(We have {{ $videos->total() }} in total!)</span>
                    </h2>
                    @include('partials.form.video-search-form', [
                        'action' => route('video.search'),
                        'theme' => 'bg-white text-gray-600',
                        'button' => 'bg-purple-600 hover:bg-purple-500 text-white'
                    ])
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-2">
                    @if($videos->total() > 0)
                        @foreach($videos as $video)
                            @include('partials.video-tile')
                        @endforeach
                    @else
                        <span class="text-xl font-light">{{ __('There is nothing here.') }}</span>
                    @endif
                </div>
                <div class="mt-5">
                    {{ $videos->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
