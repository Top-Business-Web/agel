<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('vendorHome') }}">
            <img src="{{ getFile(isset($setting) ?getFile(getAuthSetting('logo'))  : null) }}" class="header-brand-img"
                 alt="logo">
        </a>
        <!-- الشعار -->
    </div>

    <ul class="side-menu">
        <li>
            <h3>القائمة الرئيسية</h3>
        </li>

        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'vendorHome' ? 'active' : '' }}"
               href="{{ route('vendorHome') }}">
                <i class="fa fa-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسية</span>
            </a>
        </li>
        @canany(['create_branch', 'update_branch', 'delete_branch','read_branch'])
            <li class="{{ routeActive('branches.index') }}">
                <a class="slide-item {{ routeActive('branches.index') }}" style="margin-right:5px;"
                   href="{{ route('branches.index') }}">
                    <i class="fas fa-code-branch side-menu__icon"></i> <!-- Branches Icon -->
                    الفروع
                </a>
            </li>
        @endcanany
        @canany(['create_category', 'update_category', 'delete_category','read_category'])
            <li class="{{ routeActive('categories.index') }}">
                <a class="slide-item {{ routeActive('categories.index') }}" style="margin-right:8px;"
                   href="{{ route('categories.index') }}">
                    <i class="fas fa-th side-menu__icon"></i> <!-- Category Icon -->
                    التصنيفات
                </a>

            </li>
        @endcanany
        @canany(['create_stock', 'update_stock', 'delete_stock','read_stock'])

            <li class="{{ routeActive('stocks.index') }}">
                <a class="slide-item {{ routeActive('stocks.index') }}" style="margin-right:8px;"
                   href="{{ route('stocks.index') }}">
                    <i class="fa fa-star side-menu__icon"></i> <!-- stocks Icon -->
                    المخزون
                </a>
            </li>
        @endcanany
        <!-- إدارة المستخدمين -->
        @canany(['read_vendor','create_vendor', 'update_vendor', 'delete_vendor','read_investor','create_investor', 'update_investor', 'delete_investor','read_client','create_client', 'update_client', 'delete_client'])
            <li class="slide {{ arrRouteActive(['users.index', 'vendor.vendors.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['users.index', 'vendor.vendors.index'], 'active') }}"
                   data-toggle="slide" href="#">
                    <i class="fa fa-user-cog side-menu__icon"></i> <!-- User Management Icon -->
                    <span class="side-menu__label">إدارة المستخدمين</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                @canany(['read_vendor','create_vendor', 'update_vendor', 'delete_vendor'])
                    <ul class="slide-menu">
                        <!-- التجار -->
                        <li class="{{ routeActive('vendor.index') }}">
                            <a class="slide-item {{ routeActive('vendor.vendors.index') }}"
                               href="{{ route('vendor.vendors.index') }}">
                                <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                                إدارة الموظفين
                            </a>
                        </li>
                    </ul>
                @endcanany
                @canany(['read_investor','create_investor', 'update_investor', 'delete_investor'])
                    <ul class="slide-menu">
                        <li class="{{ routeActive('investors.index') }}">
                            <a class="slide-item {{ routeActive('investors.index') }}"
                               href="{{ route('investors.index') }}">
                                <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                                إدارة المستثمرين
                            </a>
                        </li>
                    </ul>
                @endcanany
                @canany(['read_client','create_client','update_client','delete_client'])
                    <ul class="slide-menu">
                        <li class="{{ routeActive('clients.index') }}">
                            <a class="slide-item {{ routeActive('clients.index') }}"
                               href="{{ route('clients.index') }}">
                                <i class="fas fa-store side-menu__icon"></i> <!-- Vendor Icon -->
                                إدارة العملاء
                            </a>
                        </li>
                    </ul>
                @endcanany
            </li>
        @endcanany
        <!-- إدارة المستخدمين -->

        {{--        <!-- إدارة الإعدادات -->--}}
        {{--        <li class="slide {{ arrRouteActive(['countries.index', 'cities.index']) }}">--}}
        {{--            <a class="side-menu__item {{ arrRouteActive(['vendor.roles.index', 'vendor.activity_logs.index'], 'active') }}"--}}
        {{--               data-toggle="slide" href="#">--}}
        {{--                <i class="fas fa-cog side-menu__icon"></i> <!-- Settings Icon -->--}}
        {{--                <span class="side-menu__label">الإعدادات</span>--}}
        {{--                <i class="angle fa fa-angle-right"></i>--}}
        {{--            </a>--}}

        {{--            <ul class="slide-menu">--}}
        {{--                <!-- الأدوار والصلاحيات -->--}}
        {{--                <li class="{{ routeActive('vendor.roles.index') }}">--}}
        {{--                    <a class="slide-item {{ routeActive('vendor.roles.index') }}" href="{{ route('vendor.roles.index') }}">--}}
        {{--                        <i class="fas fa-user-shield side-menu__icon"></i> <!-- Permissions Management Icon -->--}}
        {{--                        إدارة الأدوار والصلاحيات--}}
        {{--                    </a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}

        {{--            <ul class="slide-menu">--}}
        {{--                <!-- سجلات الأنشطة -->--}}
        {{--                <li class="{{ routeActive('activity_logs.index') }}">--}}
        {{--                    <a class="slide-item {{ routeActive('vendor.activity_logs.index') }}" href="{{ route('vendor.activity_logs.index') }}">--}}
        {{--                        <i class="fas fa-heartbeat side-menu__icon"></i> <!-- Activity Icon -->--}}
        {{--                        سجلات الأنشطة--}}
        {{--                    </a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
        {{--        <!-- إدارة الإعدادات -->--}}


        {{--        <!-- إدارة أعدادات النظام -->--}}

        {{--        <li class="slide">--}}
        {{--            <a class="side-menu__item  {{ Route::currentRouteName() == 'vendorSetting' ? 'active' : '' }}"--}}
        {{--               href="{{ route('vendorSetting') }}">--}}
        {{--                <i class="fa fa-home side-menu__icon"></i>--}}
        {{--                <span class="side-menu__label">أعدادات النظام</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}

        {{--        <!-- إدارة أعدادات النظام -->--}}


        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'vendor.logout' ? 'active' : '' }}"
               href="{{ route('vendor.logout') }}">
                <i class="fa fa-lock side-menu__icon"></i>
                <span class="side-menu__label">تسجيل الخروج</span>
            </a>
        </li>
    </ul>
</aside>
