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
            <h3>العناصر</h3>
        </li>
        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'adminHome' ? 'active' : '' }}"
               href="{{ route('adminHome') }}">
                <i class="fa fa-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسيه</span>
            </a>
        </li>
{{--        @can(['create_vendor_management', 'read_vendor_management', 'update_vendor_management', 'delete_vendor_management','create_admin_management', 'read_admin_management', 'update_admin_management', 'delete_admin_management'])--}}
            <!-- Main users Management Section -->
            <li class="slide {{ arrRouteActive(['users.index', 'admins.index', 'admin.vendors.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['users.index', 'admins.index', 'vendors.index'], 'active') }}"
                   data-toggle="slide" href="#">
                    <i class="fa fa-user-cog side-menu__icon"></i> <!-- User Management Icon -->
                    <span class="side-menu__label">إدارة المستخدمين</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
{{--                    @canany(['create_admin_management', 'read_admin_management', 'update_admin_management', 'delete_admin_management'])--}}
                        <!-- admins -->
                        <li class="{{ routeActive('admins.index') }}">
                            <a class="slide-item {{ routeActive('admins.index') }}" href="{{ route('admins.index') }}">
                                <i class="fas fa-user-tie side-menu__icon"></i> <!-- Admin Icon -->
                                المشرفين
                            </a>
                        </li>
{{--                    @endcan--}}
{{--                    @canany(['create_vendor_management', 'read_vendor_management', 'update_vendor_management', 'delete_vendor_management'])--}}
                        <!-- vendors -->
                        <li class="{{ routeActive('admin.vendors.index') }}">
                            <a class="slide-item {{ routeActive('admin.vendors.index') }}"
                               href="{{ route('admin.vendors.index') }}">
                                <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                                المكاتب
                            </a>
                        </li>
{{--                    @endcan--}}
                </ul>
            </li>
            <!-- Main users Management Section -->
{{--        @endcanany--}}
        <!-- Main locations Management Section -->
        @canany(['create_country_management', 'read_country_management', 'update_country_management', 'delete_country_management','create_city_management', 'read_city_management', 'update_city_management', 'delete_city_management','create_plan_management', 'read_plan_management', 'update_plan_management', 'delete_plan_management','create_plan_subscription_management', 'read_plan_subscription_management', 'update_plan_subscription_management', 'delete_plan_subscription_management'])
            <li
                class="slide {{ arrRouteActive(['countries.index', 'cities.index', 'Plans.index', 'planSubscription.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['countries.index', 'cities.index'], 'active') }}"
                   data-toggle="slide" href="#">
                    <i class="fas fa-map-marker-alt side-menu__icon"></i> <!-- Location Icon -->
                    <span class="side-menu__label">إدارة المواقع</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @canany(['create_country_management', 'read_country_management', 'update_country_management', 'delete_country_management'])
                        <!-- countries -->
                        <li class="{{ routeActive('countries.index') }}">
                            <a class="slide-item {{ routeActive('countries.index') }}"
                               href="{{ route('countries.index') }}">
                                <i class="fa fa-globe side-menu__icon"></i> <!-- Country Icon -->
                                الدولة
                            </a>
                        </li>
                    @endcanany
                    @canany(['create_city_management', 'read_city_management', 'update_city_management', 'delete_city_management'])
                        <li class="{{ routeActive('cities.index') }}">
                            <a class="slide-item {{ routeActive('cities.index') }}" href="{{ route('cities.index') }}">
                                <i class="fa fa-building side-menu__icon"></i> <!-- City Icon -->
                                المدينة
                            </a>
                        </li>
                    @endcanany
                    @canany(['create_plan_management', 'read_plan_management', 'update_plan_management', 'delete_plan_management'])
                        <li class="{{ routeActive('Plans.index') }}">
                            <a class="slide-item {{ routeActive('Plans.index') }}" href="{{ route('Plans.index') }}">
                                <i class="fas fa-clipboard-list side-menu__icon"></i> <!-- Plan Icon -->
                                الخطة
                            </a>
                        </li>
                    @endcanany
                    @canany(['create_plan_management', 'read_plan_management', 'update_plan_management', 'delete_plan_management','create_plan_subscription_management', 'read_plan_subscription_management', 'update_plan_subscription_management', 'delete_plan_subscription_management'])
                        <li class="{{ routeActive('planSubscription.index') }}">
                            <a class="slide-item {{ routeActive('planSubscription.index') }}"
                               href="{{ route('planSubscription.index') }}">
                                <i class="fas fa-clipboard-list side-menu__icon"></i> <!-- Plan Icon -->
                                الاشتراكات
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>
            <!-- Main locations Management Section -->
        @endcanany
        <!-- setting Management Section -->
        @canany(['read_activity_log_management', 'delete_activity_log_management'])
        <li class="slide {{ arrRouteActive(['admin.roles.index', 'admin.activity_logs.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['admin.roles.index', 'admin.activity_logs.index'], 'active') }}"
               data-toggle="slide" href="#">
                <i class="fas fa-cog side-menu__icon"></i> <!-- Settings Icon -->
                <span class="side-menu__label">إعدادات النظام</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <!-- الأدوار والصلاحيات -->
{{--                <li class="{{ routeActive('admin.roles.index') }}">--}}
{{--                    <a class="slide-item {{ routeActive('admin.roles.index') }}"--}}
{{--                       href="{{ route('admin.roles.index') }}">--}}
{{--                        <i class="fas fa-user-shield side-menu__icon"></i>--}}
{{--                        الأدوار و الصلاحيات--}}
{{--                    </a>--}}
{{--                </li>--}}
                <!-- سجل النظام -->
                <li class="{{ routeActive('admin.activity_logs.index') }}">
                    <a class="slide-item {{ routeActive('admin.activity_logs.index') }}"
                       href="{{ route('admin.activity_logs.index') }}">
                        <i class="fas fa-heartbeat side-menu__icon"></i> <!-- Activity Icon -->
                        سجل النظام
                    </a>
                </li>
            </ul>
        </li>
        <!-- setting Management Section -->
        @endcanany
        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'admin.logout' ? 'active' : '' }}"
               href="{{ route('admin.logout') }}">
                <i class="fa fa-lock side-menu__icon"></i>
                <span class="side-menu__label">تسجيل خروج</span>
            </a>
        </li>
    </ul>
</aside>
