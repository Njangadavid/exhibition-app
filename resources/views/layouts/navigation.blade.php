<nav x-data="{ open: false }" class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center w-100">
            <!-- Left side: Logo and Navigation -->
            <div class="d-flex align-items-center">
                <!-- Logo -->
                <div class="navbar-brand d-flex align-items-center me-4">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <span class="fs-2 me-2">🎨</span>
                        <span class="fw-bold fs-4 text-dark">Exhibition App</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="d-none d-md-flex align-items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-link me-3">
                        <i class="bi bi-speedometer2 me-1"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')" class="nav-link me-3">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ __('Events') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')" class="nav-link me-3">
                        <i class="bi bi-plus me-1"></i>
                        {{ __('Create Event') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right side: User Dropdown -->
            <div class="d-none d-md-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none d-flex align-items-center dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-gear me-2"></i>
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="d-md-none">
                <button @click="open = ! open" class="btn btn-link text-decoration-none p-2">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'d-block': open, 'd-none': ! open}" class="d-md-none">
        <div class="pt-2 pb-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-link px-3 py-2">
                <i class="bi bi-speedometer2 me-2"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')" class="nav-link px-3 py-2">
                <i class="bi bi-calendar-event me-2"></i>
                {{ __('Events') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')" class="nav-link px-3 py-2">
                <i class="bi bi-plus me-2"></i>
                {{ __('Create Event') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-top">
            <div class="px-3">
                <div class="fw-medium fs-6 text-dark">{{ Auth::user()->name }}</div>
                <div class="fw-medium small text-muted">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3">
                <x-responsive-nav-link :href="route('profile.edit')" class="nav-link px-3 py-2">
                    <i class="bi bi-gear me-2"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="nav-link px-3 py-2">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
