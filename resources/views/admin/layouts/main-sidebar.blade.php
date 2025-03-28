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
        @can(['create_vendor', 'read_vendor', 'update_vendor', 'delete_vendor','create_admin', 'read_admin', 'update_admin', 'delete_admin'])
            <!-- Main users Management Section -->
            <li class="slide {{ arrRouteActive(['users.index', 'admins.index', 'admin.vendors.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['users.index', 'admins.index', 'vendors.index'], 'active') }}"
                   data-toggle="slide" href="#">
                    <i class="fa fa-user-cog side-menu__icon"></i> <!-- User Management Icon -->
                    <span class="side-menu__label">إدارة المستخدمين</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @canany(['create_admin', 'read_admin', 'update_admin', 'delete_admin'])
                        <!-- admins -->
                        <li class="{{ routeActive('admins.index') }}">
                            <a class="slide-item {{ routeActive('admins.index') }}" href="{{ route('admins.index') }}">
                                <i class="fas fa-user-tie side-menu__icon"></i> <!-- Admin Icon -->
                                المشرفين
                            </a>
                        </li>
                    @endcan
                    @canany(['create_vendor', 'read_vendor', 'update_vendor', 'delete_vendor'])
                        <!-- vendors -->
                        <li class="{{ routeActive('admin.vendors.index') }}">
                            <a class="slide-item {{ routeActive('admin.vendors.index') }}"
                               href="{{ route('admin.vendors.index') }}">
                                <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                                المكاتب
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            <!-- Main users Management Section -->
        @endcanany
        <!-- Main locations Management Section -->

        <!-- Main locations Management Section -->

        @canany(['create_country', 'read_country', 'update_country', 'delete_country','create_city', 'read_city', 'update_city', 'delete_city','create_plan', 'read_plan', 'update_plan', 'delete_plan','create_plan_subscription', 'read_plan_subscription', 'update_plan_subscription', 'delete_plan_subscription'])
            <li
                class="slide {{ arrRouteActive(['countries.index', 'cities.index', 'Plans.index', 'planSubscription.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['countries.index', 'cities.index'], 'active') }}"
                   data-toggle="slide" href="#">
                    <i class="fas fa-map-marker-alt side-menu__icon"></i> <!-- Location Icon -->
                    <span class="side-menu__label">إدارة الخطط</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">

                    @canany(['create_plan', 'read_plan', 'update_plan', 'delete_plan'])
                        <li class="{{ routeActive('Plans.index') }}">
                            <a class="slide-item {{ routeActive('Plans.index') }}" href="{{ route('Plans.index') }}">
                                <i class="fas fa-clipboard-list side-menu__icon"></i> <!-- Plan Icon -->
                                الخطة
                            </a>
                        </li>
                    @endcanany
                    @canany(['create_plan', 'read_plan', 'update_plan', 'delete_plan','create_plan_subscription', 'read_plan_subscription', 'update_plan_subscription', 'delete_plan_subscription'])
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
        @canany(['read_activity_log', 'delete_activity_log'])
        <li class="slide {{ arrRouteActive(['admin.activity_logs.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['admin.activity_logs.index'], 'active') }}"
               data-toggle="slide" href="#">
                <i class="fas fa-cog side-menu__icon"></i> <!-- Settings Icon -->
                <span class="side-menu__label">إعدادات النظام</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">

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
