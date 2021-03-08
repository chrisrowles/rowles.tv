<form class="block md:flex md:flex-wrap gap-2" action="{{ $action }}" method="GET">
    <div>
        <label for="search-title"></label>
        <input id="search-title" type="text" class="form-input {{ $theme }} border-gray-300"
               name="title" placeholder="{{ __('Enter Title...') }}" value="{{ old('title') }}">
    </div>
    <div class="mt-3 sm:mt-0">
        <label for="search-producer"></label>
        <input id="search-producer" type="text"
               class="form-input {{ $theme }} border-gray-300"
               name="producer" placeholder="{{ __('Enter Producer...') }}" value="{{ old('producer') }}">
    </div>
    <div class="mt-3 sm:mt-0">
        <label for="search-genre"></label>
        <input id="search-genre" type="text"
               class="form-input {{ $theme }} border-gray-300"
               name="genre" placeholder="{{ __('Enter Genre...') }}" value="{{ old('genre') }}">
    </div>
    <button class="default-button {{ $button }} mt-3 sm:mt-0"
            type="submit">{{ __('Search') }} <i class="fas fa-search"></i>
    </button>
</form>
