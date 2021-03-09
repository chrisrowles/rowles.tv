@extends('layouts.app')
@section('content')
    <x-hero-image class="max-h-64"/>
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-dark text-white">
        <h1 class="text-2xl">{{ __('Billing') }}</h1>
    </div>
    <div class="pb-6">
        <div class="max-w-9xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1 sm:gap-10">
                <div class="w-full border border-gray-200 mx-auto mt-8 p-6 shadow">
                    <form id="signup-form" action="{{ route('billing') }}" method="post">
                        @csrf
                        <div class="flex flex-wrap mb-6 mt-8 px-6">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                Cardholder Name
                            </label>
                            <x-input id="name"
                                     type="text"
                                     class="form-input w-full"
                                     name="name"
                                     placeholder="{{ __('Enter name on card...') }}" />
                        </div>
                        <div class="flex flex-wrap mb-6 mt-8 px-6">
                            <label for="card-element" class="block text-gray-700 text-sm font-bold mb-2">
                                Credit or Debit Card Number
                            </label>
                            <div id="card-element"
                                 class="form-input w-full shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></div>
                            <small id="card-errors" class="text-red-500 mt-2"></small>

                        </div>
                        <x-button id="card-button" class="inline-block align-middle float-right mr-6 py-3">
                            {{ __('Pay Now & Subscribe') }}
                        </x-button>
                    </form>
                </div>
                <div class="w-full mx-auto border border-gray-200 mx-auto mt-8 p-6 shadow">
                    <h2 class="text-2xl">Thank you!</h2>
                    <hr class="my-3">
                    <div class="px-3">
                        <label for="product-name" class="form-label sm:mb-0">Name</label>
                        <input type="text" readonly disabled id="product-name" class="w-full bg-white border-0 px-0"
                               value="1-Month Recurring Subscription">
                        <label for="product-description" class="form-label mt-3">Description</label>
                        <p id="product-description">
                            A 1-month recurring subscription, includes full access to all videos and new uploads, and
                            unlimited downloads.
                        </p>
                        <label for="product-description" class="form-label mt-6 sm:mb-0">Price</label>
                        <p id="product-description">
                            £19.99 <span class="text-gray-300 text-2xl font-extralight">/<span class="text-gray-400 text-sm font-medium">month</span>.</span>
                        </p>
                    </div>
                </div>
            </div>
            <p class="mt-8 text-sm text-gray-400">Important Information.</p>
            <hr class="mt-2 mb-2">
            <p class="text-xs text-gray-400">
                Your card will be charged and payment processed immediately after submitting the form.
                You will receive a confirmation email to the address you signed up with confirming both your payment and
                subscription to <a class="text-blue-400 hover:text-purple-500" href="{{ config('app.url') }}">{{ config('app.url') }}</a>.
                You can cancel your subscription at any time, if you choose to do so, you will still have access until the end of the
                current billing period and your card WILL NOT be charged again.
                In order to ensure PCI and SCA compliance, payment processing is handled securely via
                <a class="text-blue-400 hover:text-purple-500" href="https://stripe.com">Stripe</a>. We DO NOT store any data other
                than the last four digits of your card number. All customer payment method data is deleted when subscriptions expire
                or are cancelled.
            </p>
            <p class="mt-2 text-xs text-gray-400">
                {{ config('app.company.name') }} reserves the right to cancel your subscription at any time without providing
                a detailed explanation, although please note that this will only occur in exceptional circumstances.
            </p>
            <p class="mt-2 text-xs text-gray-400">
                Have fun!
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config("stripe.publishable_key") }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('name');
        const cardButton = document.getElementById('card-button');
        let validCard = false;
        const cardError = document.getElementById('card-errors');
        cardElement.addEventListener('change', function(event) {

            if (event.error) {
                validCard = false;
                cardError.textContent = event.error.message;
            } else {
                validCard = true;
                cardError.textContent = '';
            }
        });
        let form = document.getElementById('signup-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const {paymentMethod, error} = await stripe.createPaymentMethod(
                'card', cardElement, {
                    billing_details: {name: cardHolderName.value}
                }
            );
            if (error) {
                _notify.send('error', error.message, 'top');
            } else {
                let hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', paymentMethod.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if(session('error'))
            _notify.send('error', '{{ session('error') }}', 'top');
            @endif
        })
    </script>
@endsection
