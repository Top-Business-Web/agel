@extends('admin.layouts.master')

@section('title')
    {{ config()->get('app.name') }}
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="">

                    </div>
                </div>
                <div class="card-body">
                    

                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">اسم المستخدم</th>
                                <th class="min-w-50px">رقم الهويه</th>
                                <th class="min-w-50px">رقم الهاتف</th>
                                <th class="min-w-50px">المكتب التابع له</th>
                                <th class="min-w-50px">رقم هاتف المكتب</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    </div>
    @include('admin.layouts.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
                }
            },
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'national_id', name: 'national_id'},
            {data: 'phone', name: 'phone'},
            {data: 'office_name', name: 'office_name'},
            {data: 'office_phone', name: 'office_phone'},
        ]
        showData('{{route($route.'.index')}}', columns);


    </script>




@endsection


