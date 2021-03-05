<x-app-layout>
    <div class="hero-image"></div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-4xl mb-4">Available Signup Packages</h1>
            <div class="grid grid-cols-3 gap-4">
                @foreach($packages as $package)
                    <div class="card bg-white">
                        <h1 class="p-4">{{ $package->name }}</h1>
                        <hr>
                        <div class="card-inner">
                            {{ $package->description }}
                        </div>
                        <hr>
                        <div class="flex items-start items-center justify-between p-4">
                            <p class="text-xl">Price: £{{ number_format($package->price / 100, 2) }}</p>
                            <button class="primary-button">Sign Up</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
