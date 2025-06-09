<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('vendorHome') }}">
            <img src="{{ getFile(isset($setting) ? getFile(getAuthSetting('logo')) : null) }}" class="header-brand-img"
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
        <li class="{{ routeActive('unsurpasseds.index') }}">
            <a class="slide-item {{ routeActive('unsurpasseds.index') }}" style="margin-right:8px;"
                href="{{ route('unsurpasseds.index') }}">
                <i class="fas fa-hand-holding-usd side-menu__icon"></i> <!-- المتعثرين -->
                المتعثرين
            </a>

        </li>

        @canany(['create_order', 'update_order', 'delete_order', 'read_order'])
            <li class="{{ routeActive('order.index') }}">
                <a class="slide-item {{ routeActive('orders.index') }}" style="margin-right:8px;"
                    href="{{ route('orders.index') }}">
                    <i class="fa fa-list side-menu__icon"></i> <!-- الطلبات Icon --> الطلبات
                </a>

            </li>
        @endcanany

        @canany(['create_stock', 'update_stock', 'delete_stock', 'read_stock'])
            <li class="{{ routeActive('stocks.index') }}">
                <a class="slide-item {{ routeActive('stocks.index') }}" style="margin-right:8px;"
                    href="{{ route('stocks.index') }}">
                    <i class="fa fa-box side-menu__icon"></i> <!-- المخزون Icon --> المخزون
                </a>
            </li>
        @endcanany


        @canany(['create_category', 'update_category', 'delete_category', 'read_category'])
            <li class="{{ routeActive('categories.index') }}">
                <a class="slide-item {{ routeActive('categories.index') }}" style="margin-right:8px;"
                    href="{{ route('categories.index') }}">
                    <i class="fas fa-th side-menu__icon"></i> <!-- Category Icon -->
                    الاصناف
                </a>

            </li>
        @endcanany

        @canany(['read_client', 'create_client', 'update_client', 'delete_client'])
            <li class="{{ routeActive('clients.index') }}">
                <a class="slide-item {{ routeActive('clients.index') }}" style="margin-right:8px;"
                    href="{{ route('clients.index') }}">
                    <i class="fa fa-address-book side-menu__icon"></i> <!-- إدارة العملاء Icon --> إدارة العملاء
                </a>
            </li>
        @endcanany

        @canany(['create_branch', 'update_branch', 'delete_branch', 'read_branch'])
            <li class="{{ routeActive('branches.index') }}">
                <a class="slide-item {{ routeActive('branches.index') }}" style="margin-right:5px;"
                    href="{{ route('branches.index') }}">
                    <i class="fas fa-code-branch side-menu__icon"></i> <!-- Branches Icon -->
                    الفروع
                </a>
            </li>
        @endcanany




        @canany(['read_investor', 'create_investor', 'update_investor', 'delete_investor'])
            <li class="{{ routeActive('investors.index') }}">
                <a class="slide-item {{ routeActive('investors.index') }}" style="margin-right:8px;"
                    href="{{ route('investors.index') }}">
                    <i class="fa fa-briefcase side-menu__icon"></i> <!-- إدارة المستثمرين Icon --> إدارة المستثمرين
                </a>
            </li>
        @endcanany

        @canany(['read_vendor', 'create_vendor', 'update_vendor', 'delete_vendor'])
            <!-- التجار -->
            <li class="{{ routeActive('vendor.index') }}">
                <a class="slide-item {{ routeActive('vendor.vendors.index') }}" style="margin-right:8px;"
                    href="{{ route('vendor.vendors.index') }}">
                    <i class="fa fa-users side-menu__icon"></i> <!-- إدارة الموظفين Icon --> إدارة الموظفين
                </a>
            </li>
        @endcanany


        @canany(['read_vendor_wallets', 'create_vendor_wallets', 'update_vendor_wallets', 'delete_vendor_wallets'])
            <!-- التجار -->
            <li class="{{ routeActive('vendor_wallets.index') }}">
                <a class="slide-item {{ routeActive('vendor_wallets.index') }}" style="margin-right:8px;"
                    href="{{ route('vendor_wallets.index') }}">
                    <i class="fas fa-wallet side-menu__icon"></i> خزانه المكتب
                </a>
            </li>
        @endcanany

        @canany(['read_investor_wallets', 'create_investor_wallets', 'update_investor_wallets',
            'delete_investor_wallets'])
            <!-- المستثمر -->
            <li class="{{ routeActive('investor_wallets.index') }}">
                <a class="slide-item {{ routeActive('investor_wallets.index') }}" style="margin-right:8px;"
                    href="{{ route('investor_wallets.index') }}">
                    <i class="fas fa-receipt side-menu__icon"></i> خزانه المستثمرين
                </a>
            </li>
        @endcanany



        <li class="{{ routeActive('vendor.plans.index') }}">
            <a class="slide-item {{ routeActive('vendor.plans.index') }}" style="margin-right:8px;"
                href="{{ route('vendor.plans.index') }}">
                <i class="fa fa-credit-card side-menu__icon"></i> <!-- الاشتراكات Icon -->
                الاشتراكات
            </a>
        </li>
        @canany(['read_activity_log', 'delete_activity_log'])
            <li class="{{ routeActive('activity_logs.index') }}">
                <a class="slide-item {{ routeActive('vendor.activity_logs.index') }}"
                    href="{{ route('vendor.activity_logs.index') }}" style="margin-right:8px;">
                    <i class="fa fa-history side-menu__icon"></i> <!-- سجلات الأنشطة Icon --> سجلات الأنشطة
                </a>
            </li>
        @endcanany




        @canany(['read_setting', 'create_setting', 'update_setting', 'delete_setting'])
            <li class="slide">
                <a class="side-menu__item  {{ Route::currentRouteName() == 'vendorSetting' ? 'active' : '' }}"
                    href="{{ route('vendorSetting') }}">
                    <i class="fa fa-cog side-menu__icon"></i> <!-- إعدادات النظام Icon --> <span
                        class="side-menu__label">أعدادات النظام</span>
                </a>
            </li>

            <!-- إدارة كلمه السر -->
        @endcanany

        @canany(['read_setting', 'create_setting', 'update_setting', 'delete_setting'])
            <li class="slide">
                <a class="side-menu__item  {{ Route::currentRouteName() == 'UpdatePassword' ? 'active' : '' }}"
                    href="{{ route('UpdatePassword') }}">
                    <i class="fas fa-key side-menu__icon"></i>

                    </i> <!-- إعدادات  كلمه السر Icon --> <span class="side-menu__label">تغير كلمه السر</span>
                </a>
            </li>

            <!-- إدارة  كلمه السر -->
        @endcanany


        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'vendor.logout' ? 'active' : '' }}"
                href="{{ route('vendor.logout') }}">
                <i class="fa fa-sign-out-alt side-menu__icon"></i> <!-- تسجيل الخروج Icon --> <span
                    class="side-menu__label">تسجيل الخروج</span>
            </a>
        </li>
    </ul>
</aside>
