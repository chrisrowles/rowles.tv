<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl">{{ __('Billing') }}</h1>
    </div>
    <div class="pb-6">
        <div class="max-w-full mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center">
                <div class="w-2/3 rounded border border-gray-200 mx-auto mt-8 p-6 clearfix">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">You are not a subscribed to a plan</strong>
                        <span class="block mt-2">To become a subscriber enter your billing info below:</span>
                    </div>

                    <form id="signup-form" action="{{ route('billing') }}" method="post">
                        @csrf
                        <div class="flex flex-wrap mb-6 mt-8 px-6">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                Name on Card
                            </label>
                            <input type="text" name="name" id="name"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700
                                   leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex flex-wrap mb-6 mt-8 px-6">
                            <label for="card-element" class="block text-gray-700 text-sm font-bold mb-2">
                                Credit Card Info
                            </label>
                            <div id="card-element"
                                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></div>
                            <div id="card-errors"
                                 class="text-red-400 text-bold mt-2 text-sm font-medium"></div>
                        </div>

                        <button type="submit" id="card-button" class="inline-block align-middle text-center select-none border font-bold whitespace-no-wrap py-2 px-4 rounded text-base leading-normal no-underline text-gray-100 bg-blue-500 hover:bg-blue-700 float-right mr-6">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    console.log(stripe);
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');
    const cardHolderName = document.getElementById('name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
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
        const { paymentMethod, error } = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: { name: cardHolderName.value }
            }
        );
        if (error) {
            // Display "error.message" to the user...
            console.log(error);
        } else {
            // The card has been verified successfully...
            let hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);
            // Submit the form
            form.submit();
        }
    });
</script>
