@extends('vendor/layouts/master')
@section('title')
    الرئيسية
@endsection


@section('content')
    <div class="text-center my-5" style="direction: rtl; font-size: 16px;">
        <h2 class="mb-3" style="font-size: 20px;">
            @if(@$vendorPlanSubscription)
                خطتك الحالية هي
                <span class="text-success fw-bold">
                {{ @$vendorPlanSubscription->plan->name }}
            </span>
                وتنتهي في
                <span class="text-danger fw-bold">
                {{ \Carbon\Carbon::parse(@$vendorPlanSubscription->to)->format('Y-m-d') }}
            </span>
            @else
                <span class="text-warning fw-bold">أنت على الخطة المجانية</span>
            @endif
        </h2>

        <a href="{{ route('vendor.plans.index') }}" class="btn btn-primary btn-sm">
            عرض جميع الخطط
        </a>
    </div>



    <div class="row">
        <form action="{{ route('vendorHome') }}" method="get" class="col-12 col-md-8 mb-3 mb-md-0">
            @csrf
            <div class="form-group mb-0">
                <label for="officeFilter" class="font-weight-bold text-muted mb-1">
                    إحصائيات المكاتب
                    <i class="fas fa-filter text-muted ml-1"></i>
                </label>

                <div class="d-flex flex-row align-items-center gap-2">
                    <!-- سنة -->
                    <div class="input-group me-2" style="min-width: 200px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                        </div>
                        <select name="year" class="form-control select2">
                            </option>
                            <option @if (@$selectedYear == 'all') selected @endif value="all">الكل</option>
                            @foreach ($years as $year)

                                <option @if ($selectedYear == $year) selected @endif value="{{ $year }}">
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- شهر -->
                    <div class="input-group me-2" style="min-width: 200px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar"></i>
                            </span>
                        </div>
                        <select name="month" class="form-control select2">
                            </option>
                            <option @if (@$selectedMonth == 'all') selected @endif value="all">الكل</option>
                            @foreach ($months as $value => $month)

                                <option @if ($selectedMonth == $value) selected @endif value="{{ $value }}">
                                    {{ $month }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- زر التصفية -->
                    <button type="submit" class="btn btn-primary"><i class="fe fe-filter"></i></button>
                </div>
            </div>
        </form>
    </div>
    <br>
    <div class="row">
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
        <!-- Third Row -->
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
    </div>
    {{-- </div> --}}
@endsection
@section('js')
@endsection
