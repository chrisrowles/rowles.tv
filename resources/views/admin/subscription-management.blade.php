@extends('layouts.app')

@section('content')
    <x-breadcrumb link="admin.subscription">
        <h1 class="text-3xl mt-1">{{ __('Subscription Management') }}</h1>
    </x-breadcrumb>
    <div class="mt-8 pb-6">
        <div class="max-w-9xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1 sm:gap-10">
                <div class="w-full border border-gray-200 mx-auto p-6 shadow flex flex-col">
                    <form class="flex-grow" action="{{ route('admin.subscription.update', ['plan' => $plan]) }}" method="POST">
                        <x-input type="hidden" name="_method" value="put"/>
                        <x-input type="hidden" name="price" id="price" value="{{ $plan->price }}"/>
                        @csrf
                        <div class="mb-4">
                            <x-label for="nickname" :value="__('Name')" class="form-label" />
                            <x-input id="nickname"
                                     type="text"
                                     class="form-input w-full"
                                     name="nickname"
                                     value="{{ $plan->nickname }}"
                                     placeholder="{{ __('Enter Name...') }}"
                                     required />
                            <small class="text-red-500 error hidden" id="nickname-error"></small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="description">{{ __('Description.') }}</label>
                            <textarea id="description" name="description" class="form-textbox" rows="10" placeholder="{{ __('Enter Description...') }}">{{ $plan->description }}</textarea>
                            <small class="text-red-500 error hidden" id="description-error"></small>
                            <small class="text-red-500 error hidden" id="email-error"></small>
                        </div>
                        <div class="mb-4">
                            <x-label for="interval" :value="__('Billing Interval')" class="form-label" />
                            <x-input id="interval"
                                     type="text"
                                     class="form-input w-full"
                                     name="interval"
                                     value="month"
                                     placeholder="{{ __('Enter Interval...') }}"
                                     required />
                            <small class="text-red-500 error hidden" id="price-error"></small>
                        </div>
                        <div class="flex items-center justify-end">
                            <x-button class="default-button">
                                {{ __('Update Package') }}
                            </x-button>
                        </div>
                    </form>
                </div>
                <div class="w-full">
                    @include('partials.subscription-plan', ['plan' => $plan])
                    <div class="w-full mx-auto border border-gray-200 mx-auto mt-8 p-6 shadow">
                        <h2 class="text-2xl">Active Subscribers</h2>
                        <hr class="my-3">
                        <div class="px-3 overflow-x-auto">
                            <table class="primary-table mt-3">
                                <thead>
                                <tr>
                                    <th scope="col" class="primary-table-header">{{ __('Name') }}</th>
                                    <th scope="col" class="primary-table-header">{{ __('Email') }}</th>
                                    <th scope="col" class="primary-table-header">{{ __('Created') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="metadata-attributes">
                                @if(count($subscribers) > 0)
                                    @foreach($subscribers as $user)
                                        <tr>
                                            <td class="text-sm py-2 whitespace-nowrap">{{ $user->name }}</td>
                                            <td class="text-sm py-2 whitespace-nowrap text-blue-400">
                                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                            </td>
                                            <td class="text-sm py-2 whitespace-nowrap">{{ $user->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center py-3" colspan="3">
                                            <em>{{ __('No active subscribers.') }}</em>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
