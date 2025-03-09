<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('vendorHome') }}">
            <img src="{{ getFile(isset($setting) ? $setting->logo : null) }}" class="header-brand-img" alt="logo">
        </a>
        <!-- LOGO -->
    </div>


    <ul class="side-menu">
        <li>
            <h3>{{ trns('elements') }}</h3>

        </li>

<<<<<<< HEAD
        {{-- --}}{{-- comments --}}
=======
        {{----}}{{--comments--}}
>>>>>>> ac2b042b685ba0dd548963753317bfbf4c666069
        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'vendorHome' ? 'active' : '' }}"
                href="{{ route('vendorHome') }}">
                <i class="fa fa-home side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('home') }}</span>
            </a>
        </li>

<<<<<<< HEAD
        <li class="{{ routeActive('branches.index') }}">
            <a class="slide-item {{ routeActive('branches.index') }}" href="{{ route('branches.index') }}">
                <i class="fas fa-code-branch side-menu__icon"></i> <!-- Branches Icon -->
                الفروع
            </a>
        </li>

=======
        <!-- Main users Management Section -->
        <li class="slide {{ arrRouteActive(['users.index', 'vendor.vendors.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['users.index', 'vendor.vendors.index'], 'active') }}"
               data-toggle="slide" href="#">
                <i class="fa fa-user-cog side-menu__icon"></i> <!-- User Management Icon -->
                <span class="side-menu__label">{{ trns('users management') }}</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">

                <!-- vendors -->
                <li class="{{ routeActive('vendor.index') }}">
                    <a class="slide-item {{ routeActive('vendor.vendors.index') }}" href="{{ route('vendor.vendors.index') }}">
                        <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                        {{ trns('vendors') }}
                    </a>
                </li>
                <!-- vendors -->
>>>>>>> ac2b042b685ba0dd548963753317bfbf4c666069


            </ul>
        </li>
        <!-- Main users Management Section -->


        <!-- setting Management Section -->
        <li class="slide {{ arrRouteActive(['countries.index', 'cities.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['vendor.roles.index', 'vendor.activity_logs.index'], 'active') }}"
               data-toggle="slide" href="#">
                <i class="fas fa-cog side-menu__icon"></i> <!-- Settings Icon -->
                <span class="side-menu__label">{{ trns('setting management') }}</span>
                <i class="angle fa fa-angle-right"></i>
            </a>


            <ul class="slide-menu">
                <!-- roles -->
                <li class="{{ routeActive('vendor.roles.index') }}">
                    <a class="slide-item {{ routeActive('vendor.roles.index') }}" href="{{ route('vendor.roles.index') }}">
                        <i class="fas fa-user-shield side-menu__icon"></i> <!-- alternative Permissions Management Icon -->
                        {{--                        <i class="fas fa-lock side-menu__icon"></i> <!-- alternative Permissions Management Icon -->--}}
                        {{--                        <i class="fas fa-key side-menu__icon"></i> <!-- alternative Permissions Management Icon -->--}}
                        {{--                        <i class="fas fa-users-cog side-menu__icon"></i> <!-- Permissions Management Icon -->--}}
                        {{ trns('roles') }}
                    </a>
                </li>
                <!-- role -->
            </ul>
            <ul class="slide-menu">
                <!-- permissions -->
                <li class="{{ routeActive('activity_logs.index') }}">
                    <a class="slide-item {{ routeActive('vendor.activity_logs.index') }}" href="{{ route('vendor.activity_logs.index') }}">
                        {{--                        <i class="fas fa-running side-menu__icon"></i> <!-- Activity Icon -->--}}
                        {{--                        <i class="fas fa-bolt side-menu__icon"></i> <!-- Activity Icon -->--}}
                        {{--                        <i class="fas fa-person-running side-menu__icon"></i> <!-- Activity Icon -->--}}
                        <i class="fas fa-heartbeat side-menu__icon"></i> <!-- Activity Icon -->
                        {{ trns('activity_logs') }}
                    </a>
                </li>
                <!-- permissions -->
            </ul>
        </li>
        <!-- setting Management Section -->










        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'vendor.logout' ? 'active' : '' }}"
                href="{{ route('vendor.logout') }}">
                <i class="fa fa-lock side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li>

    </ul>
</aside>
