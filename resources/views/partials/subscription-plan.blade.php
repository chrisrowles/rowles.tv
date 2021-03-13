<div class="w-full mx-auto border border-gray-200 mx-auto mt-8 sm:mt-0 p-6 shadow">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl">Plan {{ request()->routeIs('admin.subscription') ? 'Preview' : '' }}</h2>
        <a href="{{ route('billing.portal') }}" class="text-blue-400 text-sm">
            <i class="fas fa-external-link-alt mr-1"></i>
            {{ __('Manage Plan') }}
        </a>
    </div>
    <hr class="my-3">
    <div class="px-3">
        <label for="product-name" class="form-label sm:mb-0">Name</label>
        <input type="text" readonly disabled id="product-name" class="w-full bg-white border-0 px-0"
               value="{{ $plan->nickname }}">
        <label for="product-description" class="form-label mt-3">Description</label>
        <p id="product-description">
            {{ $plan->description }}
        </p>
        <label for="product-description" class="form-label mt-6 sm:mb-0">Price</label>
        <p id="product-description">
            £{{ $plan->unit_amount }} <span class="text-gray-300 text-2xl font-extralight">/
                <span class="text-gray-400 text-sm font-medium">month</span>.</span>
        </p>
    </div>
</div>
