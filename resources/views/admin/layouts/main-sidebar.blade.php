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

        <!-- Main users Management Section -->
        <li class="slide {{ arrRouteActive(['users.index', 'admins.index', 'admin.vendors.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['users.index', 'admins.index', 'vendors.index'], 'active') }}"
                data-toggle="slide" href="#">
                <i class="fa fa-user-cog side-menu__icon"></i> <!-- User Management Icon -->
                <span class="side-menu__label">إدارة المستخدمين</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <!-- admins -->
                <li class="{{ routeActive('admins.index') }}">
                    <a class="slide-item {{ routeActive('admins.index') }}" href="{{ route('admins.index') }}">
                        <i class="fas fa-user-tie side-menu__icon"></i> <!-- Admin Icon -->
                        المشرفين
                    </a>
                </li>
                <!-- vendors -->
                <li class="{{ routeActive('admin.vendors.index') }}">
                    <a class="slide-item {{ routeActive('admin.vendors.index') }}"
                        href="{{ route('admin.vendors.index') }}">
                        <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                        المكاتب
                    </a>
                </li>
            </ul>
        </li>
        <!-- Main users Management Section -->

        <!-- Main locations Management Section -->
        <li
            class="slide {{ arrRouteActive(['countries.index', 'cities.index', 'Plans.index', 'planSubscription.index', 'categories.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['countries.index', 'cities.index'], 'active') }}"
                data-toggle="slide" href="#">
                <i class="fas fa-map-marker-alt side-menu__icon"></i> <!-- Location Icon -->
                <span class="side-menu__label">إدارة المواقع</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li class="{{ routeActive('countries.index') }}">
                    <a class="slide-item {{ routeActive('countries.index') }}" href="{{ route('countries.index') }}">
                        <i class="fa fa-globe side-menu__icon"></i> <!-- Country Icon -->
                        الدولة
                    </a>
                </li>
                <li class="{{ routeActive('cities.index') }}">
                    <a class="slide-item {{ routeActive('cities.index') }}" href="{{ route('cities.index') }}">
                        <i class="fa fa-building side-menu__icon"></i> <!-- City Icon -->
                        المدينة
                    </a>
                </li>

                <li class="{{ routeActive('Plans.index') }}">
                    <a class="slide-item {{ routeActive('Plans.index') }}" href="{{ route('Plans.index') }}">
                        <i class="fas fa-clipboard-list side-menu__icon"></i> <!-- Plan Icon -->
                        الخطة
                    </a>
                </li>

                <li class="{{ routeActive('planSubscription.index') }}">
                    <a class="slide-item {{ routeActive('planSubscription.index') }}"
                        href="{{ route('planSubscription.index') }}">
                        <i class="fas fa-clipboard-list side-menu__icon"></i> <!-- Plan Icon -->
                        الاشتراكات
                    </a>
                </li>

                <li class="{{ routeActive('categories.index') }}">
                    <a class="slide-item {{ routeActive('categories.index') }}"
                        href="{{ route('categories.index') }}">
                        <i class="fas fa-clipboard-list side-menu__icon"></i> <!-- Plan Icon -->
                        الفئات
                    </a>
                </li>

            </ul>
        </li>
        <!-- Main locations Management Section -->

        <!-- setting Management Section -->
        <li class="slide {{ arrRouteActive(['admin.roles.index', 'admin.activity_logs.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['admin.roles.index', 'admin.activity_logs.index'], 'active') }}"
                data-toggle="slide" href="#">
                <i class="fas fa-cog side-menu__icon"></i> <!-- Settings Icon -->
                <span class="side-menu__label">إعدادات النظام</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <!-- الأدوار والصلاحيات -->
                <li class="{{ routeActive('admin.roles.index') }}">
                    <a class="slide-item {{ routeActive('admin.roles.index') }}"
                        href="{{ route('admin.roles.index') }}">
                        <i class="fas fa-user-shield side-menu__icon"></i>
                        الأدوار و الصلاحيات
                    </a>
                </li>
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

        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'admin.logout' ? 'active' : '' }}"
                href="{{ route('admin.logout') }}">
                <i class="fa fa-lock side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li>
    </ul>
</aside>
