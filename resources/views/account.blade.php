@extends('layouts.app')

@section('content')
    <x-breadcrumb link="account">
        <h1 class="text-3xl mt-1">{{ __('Account Management') }}</h1>
    </x-breadcrumb>
    <div class="mt-8 pb-6">
        <div class="max-w-9xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1 sm:gap-10">
                <div class="w-full border border-gray-200 mx-auto p-6 shadow flex flex-col">
                    <form class="flex-grow" action="/" method="POST">
                        <x-input type="hidden" name="_method" value="put"/>
                        @csrf
                        <div class="mb-4">
                            <x-label for="name" :value="__('Name')" class="form-label" />
                            <x-input id="name"
                                     type="text"
                                     class="form-input w-full"
                                     name="name"
                                     value="{{ $user->name }}"
                                     placeholder="{{ __('Enter Name...') }}"
                                     required />
                            <small class="text-red-500 error hidden" id="name-error"></small>
                        </div>
                        <div class="mb-4">
                            <x-label for="producer" :value="__('Email Address')" class="form-label" />
                            <x-input id="producer"
                                     type="email"
                                     class="form-input w-full"
                                     name="email"
                                     value="{{ $user->email }}"
                                     placeholder="{{ __('Enter Email...') }}" />
                            <small class="text-red-500 error hidden" id="email-error"></small>
                        </div>
                        <div class="block mt-6">
                            <label for="email_updates" class="inline-flex items-center">
                                <input id="email_updates" type="checkbox"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       name="remember" checked>
                                <span class="ml-2 text-sm">{{ __('I want to receive emails for updates on new releases.') }}</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end">
                            <x-button class="default-button">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                    <div class="my-auto">
                        <p class="text-sm text-gray-400">Important Information.</p>
                        <hr class="mt-2 mb-2">
                        <p class="text-xs text-gray-400">
                            We do not share your data with any third-parties, or use any tracking, analytics or other third-party
                            monitoring tools. We only store the name and email address you provided to us when you signed up for an
                            account, all payment data is handled through <a href="https://stripe.com">Stripe</a>, no payment method
                            details are stored on our servers.
                        </p>
                    </div>
                </div>
                <div class="w-full">
                    @include('partials.subscription-plan', ['plan' => $plan])
                    <div class="w-full mx-auto border border-gray-200 mx-auto mt-8 p-6 shadow">
                        <h2 class="text-2xl">Payment History</h2>
                        <hr class="my-3">
                        <div class="px-3 overflow-x-auto">
                            <table class="primary-table mt-3">
                                <thead>
                                <tr>
                                    <th scope="col" class="primary-table-header">{{ __('Date') }}</th>
                                    <th scope="col" class="primary-table-header">{{ __('Billing Period') }}</th>
                                    <th scope="col" class="primary-table-header">{{ __('Amount') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="metadata-attributes">
                                    <tr>
                                        <td class="text-sm py-2 whitespace-nowrap">08<span class="text-xs">th</span> March 2021</td>
                                        <td class="text-sm py-2 whitespace-nowrap">08<span class="text-xs">th</span> March 2021 - 08<span class="text-xs">th</span> April 2021</td>
                                        <td class="text-sm py-2 whitespace-nowrap">£19.99</td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm py-2 whitespace-nowrap">08<span class="text-xs">th</span> February 2021</td>
                                        <td class="text-sm py-2 whitespace-nowrap">08<span class="text-xs">th</span> February 2021 - 08<span class="text-xs">th</span> March 2021</td>
                                        <td class="text-sm py-2 whitespace-nowrap">£19.99</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
