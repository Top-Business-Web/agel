<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('adminHome') }}">
            <img src="{{ getFile(isset($setting) ? $setting->logo : null) }}" class="header-brand-img" alt="logo">
        </a>
        <!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li>
            <h3>{{ trns('elements') }}</h3>
        </li>
        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'adminHome' ? 'active' : '' }}"
                href="{{ route('adminHome') }}">
                <i class="fa fa-home side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('home') }}</span>
            </a>
        </li>

        <!-- Main users Management Section -->
        <li class="slide {{ arrRouteActive(['users.index', 'admins.index', 'vendors.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['users.index', 'admins.index', 'vendors.index'], 'active') }}"
                data-toggle="slide" href="#">
                <i class="fa fa-user-cog side-menu__icon"></i> <!-- User Management Icon -->
                <span class="side-menu__label">{{ trns('users management') }}</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">

                <!-- admins -->
                <li class="{{ routeActive('admins.index') }}">
                    <a class="slide-item {{ routeActive('admins.index') }}" href="{{ route('admins.index') }}">
                        <i class="fas fa-user-tie side-menu__icon"></i> <!-- Admin Icon -->
                        {{ trns('admins') }}
                    </a>
                </li>
                <!-- admins -->

                <!-- vendors -->
                <li class="{{ routeActive('vendors.index') }}">
                    <a class="slide-item {{ routeActive('vendors.index') }}" href="{{ route('vendors.index') }}">
                        <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                        {{ trns('vendors') }}
                    </a>
                </li>
                <!-- vendors -->


            </ul>
        </li>
        <!-- Main users Management Section -->





        <!-- Main locations Management Section -->
        <li class="slide {{ arrRouteActive(['countries.index', 'cities.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['countries.index', 'cities.index'], 'active') }}"
                data-toggle="slide" href="#">
                <i class="fas fa-map-marker-alt side-menu__icon"></i> <!-- Location Icon -->
                <span class="side-menu__label">{{ trns('location management') }}</span>
                <i class="angle fa fa-angle-right"></i>
            </a>

            <ul class="slide-menu">
                <!-- countries -->
                <li class="{{ routeActive('countries.index') }}">
                    <a class="slide-item {{ routeActive('countries.index') }}" href="{{ route('countries.index') }}">
                        <i class="fa fa-globe side-menu__icon"></i> <!-- Country Icon -->
                        {{ trns('country') }}
                    </a>
                </li>
                <!-- countries -->
            </ul>


            <ul class="slide-menu">
                <!-- cities -->
                <li class="{{ routeActive('cities.index') }}">
                    <a class="slide-item {{ routeActive('cities.index') }}" href="{{ route('cities.index') }}">
                        <i class="fa fa-building side-menu__icon"></i> <!-- City Icon -->
                        {{ trns('city') }}
                    </a>
                </li>
                <!-- cities -->
            </ul>

            <ul class="slide-menu">
                <!-- cities -->
                <li class="{{ routeActive('branches.index') }}">
                    <a class="slide-item {{ routeActive('branches.index') }}" href="{{ route('branches.index') }}">
                        <i class="fa fa-building side-menu__icon"></i> <!-- City Icon -->
                        {{ trns('branches') }}
                    </a>
                </li>
                <!-- branches -->
            </ul>

            <ul class="slide-menu">
                <!-- cities -->
                <li class="{{ routeActive('Plans.index') }}">
                    <a class="slide-item {{ routeActive('Plans.index') }}" href="{{ route('Plans.index') }}">
                        <i class="fa fa-building side-menu__icon"></i> <!-- City Icon -->
                        {{ trns('Plans') }}
                    </a>
                </li>
                <!-- branches -->
            </ul>

        </li>
        <!-- Main locations Management Section -->


        <!-- setting Management Section -->
        <li class="slide {{ arrRouteActive(['countries.index', 'cities.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['roles.index', 'activity_logs.index'], 'active') }}"
               data-toggle="slide" href="#">
                <i class="fas fa-cog side-menu__icon"></i> <!-- Settings Icon -->
                <span class="side-menu__label">{{ trns('setting management') }}</span>
                <i class="angle fa fa-angle-right"></i>
            </a>


            <ul class="slide-menu">
                <!-- roles -->
                <li class="{{ routeActive('roles.index') }}">
                    <a class="slide-item {{ routeActive('roles.index') }}" href="{{ route('roles.index') }}">
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
                    <a class="slide-item {{ routeActive('activity_logs.index') }}" href="{{ route('activity_logs.index') }}">
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
            <a class="side-menu__item  {{ Route::currentRouteName() == 'settingIndex' ? 'active' : '' }}"
                href="#">
                <i class="fa fa-wrench side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('settings') }}</span>
            </a>
        </li>



{{--        <li class="slide">--}}
{{--            <a class="side-menu__item  {{ Route::currentRouteName() == 'settingIndex' ? 'active' : '' }}"--}}
{{--                href="{{ route('settingIndex') }}">--}}
{{--                <i class="fa fa-wrench side-menu__icon"></i>--}}
{{--                <span class="side-menu__label">{{ trns('settings') }}</span>--}}
{{--            </a>--}}
{{--        </li>--}}


        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'admin.logout' ? 'active' : '' }}"
                href="{{ route('admin.logout') }}">
                <i class="fa fa-lock side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li>
    </ul>

</aside>
