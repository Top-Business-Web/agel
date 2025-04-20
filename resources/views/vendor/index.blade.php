@extends('vendor/layouts/master')
@section('title')
    الرئيسية
@endsection


@section('content')
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
    {{-- </div> --}}
@endsection
@section('js')
@endsection
