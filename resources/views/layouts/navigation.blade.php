<nav x-data="{ open: false }" class="bg-dark border-dark shadow fixed w-full z-50">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-{{ $height }}">
            <div class="flex">
                <x-logo-text/>
                <div class="hidden space-x-8 sm:ml-10 sm:flex text-white">
                    <x-nav-link :href="route('video.index')" :active="request()->routeIs('video.index')">
                        {{ __('Videos') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="flex gap-6">
                @admin
                    <div class="hidden sm:flex sm:items-center">
                        <!-- Admin dropdown -->
                        <x-dropdown>
                            <!-- Admin dropdown button -->
                            <x-slot name="trigger">
                                <button class="dropdown-trigger">
                                    <i class="fa fa-cogs"></i> <x-caret />
                                </button>
                            </x-slot>
                            <!-- Admin dropdown menu -->
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.subscription')">
                                    <i class="fa fa-list mr-2"></i> {{ __('Subscriptions') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.video')">
                                    <i class="fa fa-video mr-2"></i> {{ __('Videos') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endadmin
                @auth
                    <div class="hidden sm:flex sm:items-center">
                        <!-- User dropdown -->
                        <x-dropdown>
                            <!-- User dropdown button -->
                            <x-slot name="trigger">
                                <button class="dropdown-trigger">
                                    <i class="fa fa-user"></i> <x-caret />
                                </button>
                            </x-slot>
                            <!-- User dropdown menu -->
                            <x-slot name="content">
                                <x-dropdown-link :href="route('account.index')">
                                    <i class="fa fa-cog mr-2"></i> {{ __('Account Settings') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('billing.portal')">
                                    <i class="fa fa-external-link-alt mr-2"></i> {{ __('Your Subscription') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="e.preventDefault(); this.closest('form').submit();">
                                        <i class="fa fa-sign-out-alt mr-2"></i> {{ __('Log out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="hidden sm:flex items-center">
                        <x-nav-link class="h-full" :href="route('login')">
                            {{ __('Login') }}
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <!-- Responsive navigation menu button -->
                <x-hamburger />
            </div>
        </div>
    </div>

    <!-- Responsive navigation menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-4 pb-1 border-t border-purple-300">
            @auth
                <div class="flex items-center px-4 mb-4">
                    <i class="fa text-xl mr-3 fa-user text-white"></i>
                    <div>
                        <div class="font-medium text-base text-gray-300">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('account.index')">
                        <i class="fa text-xl mr-2 fa-cog text-white"></i>
                        {{ __('Account Settings') }}
                    </x-responsive-nav-link>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('billing.portal')">
                        <i class="fa text-xl mr-2 fa-external-link-alt text-white"></i>
                        {{ __('Manage Your Subscription') }}
                    </x-responsive-nav-link>
                </div>
                @admin
                    <div class="my-3 space-y-1">
                        <span class="pl-3 pr-4 py-2 text-gray-300 underline">{{ __('Administration') }}</span>
                        <x-responsive-nav-link :href="route('admin.video')">
                            <i class="fa text-xl mr-2 fa-video text-white"></i>
                            {{ __('Manage Videos') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.subscription')">
                            <i class="fa text-xl mr-2 fa-list text-white"></i>
                            {{ __('Manage Subscriptions') }}
                        </x-responsive-nav-link>
                    </div>
                @endadmin
                <div class="mt-3 space-y-1">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="e.preventDefault(); this.closest('form').submit();">
                            <i class="fa text-xl mr-2 fa-sign-out-alt text-white"></i> {{ __('Log out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
