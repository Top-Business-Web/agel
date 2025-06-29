@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | المشرفين
@endsection
@section('page_name')
    المشرفين
@endsection
@section('content')

{{--    <div class="row align-items-center">--}}
    <!-- Office Filter -->


                {{--                        <!-- Optional Title Area -->--}}
                {{--                        <div class="col-md-2 text-md-right">--}}
                {{--                            <h3 class="card-title mb-0 text-primary">إعدادات التصفية</h3>--}}
                {{--                            <small class="text-muted">استخدم الفلاتر للبحث الدقيق</small>--}}
                {{--                        </div>--}}




    <div class="row">


        <form action="{{ route('admin.statistics.filter') }}" method="POST" class="col-12 col-md-8 mb-3 mb-md-0">
            @csrf
            <div class="form-group mb-0">
                <label for="officeFilter" class="font-weight-bold text-muted mb-1">المكتب
                </label>
                <div class="d-flex flex-wrap gap-2">
                    <div class="input-group flex-grow-1 me-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-building"></i>
                            </span>
                        </div>
                        <select name="year" id="officeFilter" class="form-control select2"
                                aria-describedby="officeHelp">
                            <option @if(@$selectedYear=="") selected @endif disabled selected value="">إختر السنه</option>
                            <option @if(@$selectedYear=="all") selected @endif value="all">الكل</option>
                            @foreach ($years as $year)
                                <option @if(@$selectedYear==$year) selected @endif value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mr-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-building"></i>
                            </span>
                        </div>
                        <select name="month" id="officeFilter" class="form-control select2"
                                aria-describedby="officeHelp">
                            <option @if(@$selectedMonth=="") selected @endif disabled selected value="">إختر الشهر</option>
                            <option @if(@$selectedMonth=="all") selected @endif value="all">الكل</option>
                            @foreach ($months as $value=> $month)
                                <option @if(@$selectedMonth==$month) selected
                                        @endif value="{{ $value }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <small id="officeHelp" class="form-text text-muted m-2">
                    حدد السنه و الشهر لتصفية الإحصائيات
                </small>
                <button type="submit" class="btn btn-primary mt-3">تصفية</button>
            </div>
        </form>
    </div>
<div class="row">

        <!-- Branch Filter - Hidden Initially -->
        <div class="col-md-5 mb-3 mb-md-0" id="branch-div" style="display: none">
            <div class="form-group mb-0">
                <label for="branchFilter" class="font-weight-bold text-muted mb-1">فرع المكتب</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-code-branch"></i>
                        </span>
                    </div>
                    <select id="branchFilter" class="form-control select2"
                            aria-describedby="branchHelp">
                        <option selected value="all">كل الفروع</option>
{{--                        @foreach ($branches as $branch)--}}
{{--                            <option value="{{ $branch->id }}"--}}
{{--                                    data-office-id="{{ $branch->vendor?->parent_id != null ? $branch->vendor?->parent->id : $branch->vendor?->id }}">--}}
{{--                                {{ $branch->name }}--}}
{{--                                ({{ $branch->vendor?->parent_id != null ? $branch->vendor->parent->name : $branch->vendor?->name }}--}}
{{--                                )--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>
                <small id="branchHelp" class="form-text text-muted">
                    اختر فرع معين من الفروع التابعة للمكتب المحدد
                </small>
            </div>
        </div>


        <!-- Admins Card -->
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{ $totalOrdersAmounts }}</h2>
                        <p class="text-white mb-0">إجمالي قيمة الطلبات</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{ $totalOfficesAmounts }}</h2>
                        <p class="text-white mb-0">إجمالي رصيد المكاتب</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{ $totalUnSurpassedMoneyAmounts }}</h2>
                        <p class="text-white mb-0">إجمالي المبالغ المتعثرة</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{ $totalPayedUnsurpassedMoneyAmounts }}</h2>
                        <p class="text-white mb-0">إجمالي المبالغ المدفوعة المتعثرة</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
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
