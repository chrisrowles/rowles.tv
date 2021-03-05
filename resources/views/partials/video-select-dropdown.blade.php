<label for="video-dropdown" class="hidden">{{ __('Select Video') }}</label>
<select id="video-dropdown" class="w-full text-xs border-gray-300" x-model="id">
    <option :value="false" x-text="'-- Please Select --'"></option>
    <template x-for="video in list.options">
        <option :value="video.id" x-text="video.title"></option>
    </template>
</select>
<button @click="access()" class="w-1/2 primary-button">{{ __('Select Video') }}</button>
