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

        {{----}}{{--comments --}}
        <li class="slide">
            <a class="side-menu__item  {{ Route::currentRouteName() == 'vendorHome' ? 'active' : '' }}"
                href="{{ route('vendorHome') }}">
                <i class="fa fa-home side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('home') }}</span>
            </a>
        </li>













        <li class="slide">
            <a class="side-menu__item {{ Route::currentRouteName() == 'vendor.logout' ? 'active' : '' }}"
                href="{{ route('vendor.logout') }}">
                <i class="fa fa-lock side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li>

    </ul>
</aside>
