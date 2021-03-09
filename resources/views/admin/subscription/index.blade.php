@extends('layouts.app')

@section('content')
    <x-hero-image class="max-h-40"/>
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-dark text-white flex flex-wrap justify-between items-center">
        <h1 class="text-2xl">{{ __('Subscription Package Management') }}</h1>
        @if($packages)
            <div class="flex flex-col">
                <span class="text-gray-400 text-sm">Plan: {{ $packages->name }}</span>
                <span class="text-gray-400 text-sm">
                    Price: £19.99
                    <span class="text-xs">/month</span>
                </span>
            </div>
        @endif
    </div>
    <div class="pb-6">
        <div class="max-w-9xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1 sm:gap-10">
                <div class="w-full border border-gray-200 mx-auto mt-8 p-6 shadow flex flex-col">
                    <form class="flex-grow" action="{{ route('admin.subscription.update', ['package' => $packages]) }}" method="POST">
                        <x-input type="hidden" name="_method" value="put"/>
                        <x-input type="hidden" name="price_id" id="price_id" value="{{ $packages->price }}"/>
                        @csrf
                        <div class="mb-4">
                            <x-label for="name" :value="__('Name')" class="form-label" />
                            <x-input id="name"
                                     type="text"
                                     class="form-input w-full"
                                     name="name"
                                     value="{{ $packages->name }}"
                                     placeholder="{{ __('Enter Name...') }}"
                                     required />
                            <small class="text-red-500 error hidden" id="name-error"></small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="description">{{ __('Description.') }}</label>
                            <textarea id="description" name="description" class="form-textbox" rows="10" placeholder="{{ __('Enter Description...') }}">{{ $packages->description }}</textarea>
                            <small class="text-red-500 error hidden" id="description-error"></small>
                            <small class="text-red-500 error hidden" id="email-error"></small>
                        </div>
                        <div class="mb-4">
                            <x-label for="price" :value="__('Billing Interval')" class="form-label" />
                            <x-input id="price"
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
                    <div class="w-full mx-auto border border-gray-200 mx-auto mt-8 p-6 shadow">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl">Plan Preview</h2>
                            <a href="{{ route('billing.portal') }}" class="text-blue-400 text-sm"><i class="fas fa-external-link-alt mr-1"></i>{{ __('Preview Portal') }}</a>
                        </div>
                        <hr class="my-3">
                        <div class="px-3">
                            <label for="product-name" class="form-label sm:mb-0">Name</label>
                            <input type="text" readonly disabled id="product-name" class="w-full bg-white border-0 px-0"
                                   value="{{ $packages->name }}">
                            <label for="product-description" class="form-label mt-3">Description</label>
                            <p id="product-description">
                                {{ $packages->description }}
                            </p>
                            <label for="product-description" class="form-label mt-6 sm:mb-0">Price</label>
                            <p id="product-description">
                                £19.99 <span class="text-gray-300 text-2xl font-extralight">/<span class="text-gray-400 text-sm font-medium">month</span>.</span>
                            </p>
                        </div>
                    </div>
                    <div class="w-full mx-auto border border-gray-200 mx-auto mt-8 p-6 shadow">
                        <h2 class="text-2xl">Active Subscribers</h2>
                        <hr class="my-3">
                        <div class="px-3">
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
                                            <td class="text-sm py-2 whitespace-nowrap">{{ $user->email }}</td>
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
