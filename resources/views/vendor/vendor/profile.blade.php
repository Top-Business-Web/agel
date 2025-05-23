@extends('vendor/layouts/master')
@section('title') {{isset($setting) ? $setting->title : ''}} |  الملف الشخصي @endsection

@section('page_name')
    الملف الشخصي
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user text-center">
                        <div class="wideget-user-desc">
                            <div class="wideget-user-img">
{{--                                <img class="" src="{{ $vendor->image ? asset($vendor->image) : asset('assets/uploads/avatar.png')}}" alt="img">--}}
                            </div>
                            <div class="user-wrap">
                                <h4 class="mb-1 text-capitalize">{{$vendor->name}}</h4>
{{--                                <h6 class="text-muted mb-4"> {{$vendor->created_at->diffForHumans()}}</h6>--}}
                                <h6 class="text-muted mb-4"> {{$vendor->email}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-51" class="active show" data-toggle="tab">معلومات</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-51">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading">
                                    <h5><strong>معلومات المستخدم</strong></h5>
                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td class="text-capitalize"><strong>  الإسم :</strong> {{$vendor->name??"vendor"}}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong> البريد الإلكتروني :</strong> {{$vendor->email}}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong> تاريخ التسجيل :</strong> {{$vendor->created_at->diffForHumans()}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>

@endsection


