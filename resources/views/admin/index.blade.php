@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | المشرفين
@endsection
@section('page_name')
    المشرفين
@endsection
@section('content')
    <div class="row">
        <!-- Admins Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $admins }}</h2>
                            <p class="text-white mb-0">عدد المشرفين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offices Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $offices }}</h2>
                            <p class="text-white mb-0">عدد المكاتب</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-home text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendors Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $vendors }}</h2>
                            <p class="text-white mb-0">عدد البائعين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-users text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branches Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $branches }}</h2>
                            <p class="text-white mb-0">عدد الفروع</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-map-pin text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $regions }}</h2>
                            <p class="text-white mb-0">عدد المناطق</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-navigation text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $stock }}</h2>
                            <p class="text-white mb-0">المخزون</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-package text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plans Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $plans }}</h2>
                            <p class="text-white mb-0">عدد الخطط</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-calendar text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $activeSubscriptions }}</h2>
                            <p class="text-white mb-0">الاشتراكات النشطة</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-check-circle text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requested Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $requestedSubscriptions }}</h2>
                            <p class="text-white mb-0">الاشتراكات المطلوبة</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-clock text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Subscriptions Card -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $rejectedSubscriptions }}</h2>
                            <p class="text-white mb-0">الاشتراكات المرفوضة</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-x-circle text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
