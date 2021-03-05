<form action="/" method="POST" @submit.prevent="update()">
    <input type="hidden" name="_method" value="put"/>
    @csrf
    <div class="mb-4">
        <label class="form-label" for="title">{{ __('Title') }}</label>
        <input class="form-input w-full" id="title" type="text" placeholder="{{ __('Enter Title...') }}"
               x-ref="selectedVideoTitle">
        <small class="text-red-500 error hidden" id="title-error"></small>
    </div>
    <div class="mb-4">
        <label class="form-label" for="producer">{{ __('Producer') }}</label>
        <input class="form-input w-full" id="producer" type="text" placeholder="{{ __('Enter Producer...') }}"
               x-ref="selectedVideoProducer">
        <small class="text-red-500 error hidden" id="producer-error"></small>
    </div>
    <div class="mb-4">
        <label class="form-label" for="genre">{{ __('Genre') }}</label>
        <input class="form-input w-full" id="genre" type="text" placeholder="{{ __('Enter Genre...') }}"
               x-ref="selectedVideoGenre">
        <small class="text-red-500 error hidden" id="genre-error"></small>
    </div>
    <div class="mb-4">
        <label class="form-label" for="description">{{ __('Description.') }}</label>
        <textarea id="description" class="form-textbox" rows="13" placeholder="{{ __('Enter Description...') }}"
                  x-ref="selectedVideoDescription"></textarea>
        <small class="text-red-500 error hidden" id="description-error"></small>
    </div>
    <div class="flex items-center justify-between">
        <button class="primary-button" type="submit">
            {{ __('Submit') }}
        </button>
    </div>
</form>
