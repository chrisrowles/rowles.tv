<div class="py-6 px-4 sm:px-6 lg:px-8 bg-dark text-white flex flex-col">
    <div class="breadcrumbs">
        @isset($value)
            {{ Breadcrumbs::render($link, $value) }}
        @else
            {{ Breadcrumbs::render($link) }}
        @endisset
    </div>
    {{ $slot }}
</div>

