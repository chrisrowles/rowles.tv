<table class="primary-table mt-3">
    <thead>
    <tr>
        <th scope="col" class="primary-table-header">{{ __('Attribute') }}</th>
        <th scope="col" class="primary-table-header">{{ __('Value') }}</th>
    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="metadata-attributes">
        @if(!isset($video))
            <tr>
                <td class="text-center py-3" colspan="2">
                    <em>{{ __('There is nothing here.') }}</em>
                </td>
            </tr>
        @endif
    </tbody>
</table>
