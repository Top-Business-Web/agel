@extends('vendor/layouts/master')
@section('title')
    الرئيسية
@endsection


@section('content')
    <div class="text-center my-5" style="direction: rtl; font-size: 16px;">
        <h2 class="mb-3" style="font-size: 20px;">
            @if($vendorPlanSubscription)
                خطتك الحالية هي
                <span class="text-success fw-bold">
                {{ $vendorPlanSubscription->plan->name }}
            </span>
                وتنتهي في
                <span class="text-danger fw-bold">
                {{ \Carbon\Carbon::parse($vendorPlanSubscription->to)->format('Y-m-d') }}
            </span>
            @else
                <span class="text-warning fw-bold">أنت على الخطة المجانية</span>
            @endif
        </h2>

        <a href="{{ route('vendor.plans.index') }}" class="btn btn-primary btn-sm">
            عرض جميع الخطط
        </a>
    </div>


    {{-- <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ \App\Models\Admin::count() }}</h2>
                            <p class="text-white mb-0"> عدد المشرفين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
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
                            <h2 class="mb-0 number-font">{{ \App\Models\Vendor::count() }}</h2>
                            <p class="text-white mb-0"> عدد الشركاء </p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ \App\Models\Country::count() }}</h2>
                            <p class="text-white mb-0">عدد الدول </p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
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
                            <h2 class="mb-0 number-font">{{ \App\Models\City::count() }}</h2>
                            <p class="text-white mb-0"> عدد المدن</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
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
                            <h2 class="mb-0 number-font">{{ \App\Models\Plan::count() }}</h2>
                            <p class="text-white mb-0"> عدد الخطط</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Third Row -->
{{--        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">--}}
    {{--            <div class="card bg-primary-gradient img-card box-success-shadow">--}}
    {{--                <div class="card-body">--}}
    {{--                    <div class="d-flex">--}}
    {{--                        <div class="text-white">--}}
    {{--                            <h2 class="mb-0 number-font">{{ \App\Models\PlanDetail::count() }}</h2>--}}
    {{--                            <p class="text-white mb-0"> عدد تفاصيل الخطط</p>--}}
    {{--                        </div>--}}
    {{--                        <div class="mr-auto">--}}
    {{--                            <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{-- <div class="row">
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
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{ \App\Models\Client::count() }}</h2>
                        <p class="text-white mb-0"> عدد العملاء</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Third Row --> --}}
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font">{{ \App\Models\Category::count() }}</h2>
                        <p class="text-white mb-0"> عدد الأصناف</p>
                    </div>
                    <div class="mr-auto">
                        <i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
@endsection
@section('js')
@endsection
