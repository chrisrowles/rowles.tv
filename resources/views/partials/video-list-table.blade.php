<table class="primary-table mt-3">
    <thead>
    <tr>
        <th scope="col" class="primary-table-header">#</th>
        <th scope="col" class="primary-table-header">{{ __('Producer') }}</th>
        <th scope="col" class="primary-table-header">{{ __('Title') }}</th>
    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="video-list">
    @if($videos->total() > 0)
        <template x-for="video in data.rows">
            <tr @click="access(video.id)" class="cursor-pointer hover:underline hover:text-blue-400">
                <td>
                    <span x-text="video.id"></span>
                </td>
                <td>
                    <span x-text="video.producer"></span>
                </td>
                <td>
                    <span x-text="video.title"></span>
                </td>
            </tr>
        </template>
    @else
        <tr>
            <td class="text-center py-3" colspan="2">
                <em>{{ __('There is nothing here.') }}</em>
            </td>
        </tr>
    @endif
    </tbody>
</table>
