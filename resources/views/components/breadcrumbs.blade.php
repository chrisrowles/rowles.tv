@unless ($breadcrumbs->isEmpty())
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-200 overflow-hidden shadow-sm">
            <div class="p-2 bg-dark text-white px-5">
                <ol class="breadcrumb flex gap-2">
                    @foreach ($breadcrumbs as $breadcrumb)

                        @if (!is_null($breadcrumb->url) && !$loop->last)
                            <li class="breadcrumb-item">
                                <a class="text-blue-400" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                            </li>
                            <span class="text-gray-400">/</span>
                        @else
                            <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
@endunless
