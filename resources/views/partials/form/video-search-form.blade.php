<form class="block md:flex md:flex-wrap gap-2" action="{{ $action }}" method="GET">
    <div>
        <x-label for="search-title" :value="__('Search by Title')" class="hidden" />
        <x-input id="search-title"
                 type="text"
                 class="form-input {{ $theme }} border-gray-300"
                 name="title"
                 :value="old('title')"
                 placeholder="{{ __('Enter Title...') }}" />
    </div>
    <div class="mt-3 sm:mt-0">
        <x-label for="search-producer" :value="__('Search by Producer')" class="hidden" />
        <x-input id="search-producer"
                 type="text"
                 class="form-input {{ $theme }} border-gray-300"
                 name="producer"
                 :value="old('producer')"
                 placeholder="{{ __('Enter Producer...') }}" />
    </div>
    <div class="mt-3 sm:mt-0">
        <x-label for="search-genre" :value="__('Search by Genre')" class="hidden" />
        <x-input id="search-genre"
                 type="text"
                 class="form-input {{ $theme }} border-gray-300"
                 name="genre"
                 :value="old('genre')"
                 placeholder="{{ __('Enter Genre...') }}" />
    </div>
    <button class="default-button {{ $button }} mt-3 sm:mt-0"
            type="submit">{{ __('Search') }} <i class="fas fa-search"></i>
    </button>
</form>
