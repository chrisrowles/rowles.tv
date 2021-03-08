<form action="/" method="POST" @submit.prevent="update()">
    <input type="hidden" name="_method" value="put"/>
    @csrf
    <div class="mb-4">
        <x-label for="title" :value="__('Title')" class="form-label" />
        <x-input id="title"
                 type="text"
                 class="form-input w-full"
                 name="title"
                 x-ref="selectedVideoTitle"
                 placeholder="{{ __('Enter Title...') }}"
                 required />
        <small class="text-red-500 error hidden" id="title-error"></small>
    </div>
    <div class="mb-4">
        <x-label for="producer" :value="__('Producer')" class="form-label" />
        <x-input id="producer"
                 type="text"
                 class="form-input w-full"
                 name="producer"
                 x-ref="selectedVideoProducer"
                 placeholder="{{ __('Enter Producer...') }}" />
        <small class="text-red-500 error hidden" id="producer-error"></small>
    </div>
    <div class="mb-4">
        <x-label for="genre" :value="__('Genre')" class="form-label" />
        <x-input id="genre"
                 type="text"
                 class="form-input w-full"
                 name="genre"
                 x-ref="selectedVideoGenre"
                 placeholder="{{ __('Enter Genre...') }}" />
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
