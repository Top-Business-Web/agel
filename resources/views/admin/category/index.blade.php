@extends('admin/layouts/master')

@section('title')
{{ config()->get('app.name') }} | المكاتب
@endsection
@section('page_name')
@endsection


@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3></h3>
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
{{--                                <th class="min-w-25px">--}}
{{--                                    <input type="checkbox" id="select-all">--}}
{{--                                </th>--}}
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px rounded-end">الإسم</th>
                                <th class="min-w-50px rounded-end">المكتب</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

     </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            // {
            //     data: 'checkbox',
            //     name: 'checkbox',
            //     orderable: false,
            //     searchable: false,
            //     render: function (data, type, row) {
            //         return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
            //     }
            // },
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'vendor', name: 'vendor'},

        ]
        showData('{{route($route.'.index')}}', columns);


        // Hide the first column and remove checkboxes
        $('#dataTable').on('draw.dt', function () {
            var table = $('#dataTable').DataTable();
            table.column(0).visible(false);
            $('.select-all-checkbox, .delete-checkbox, .checkbox-selector').remove();
        });

        {{--// Delete Using Ajax--}}
        {{--deleteScript('{{route($route.'.destroy',':id')}}');--}}
        {{--deleteSelected('{{route($route.'.deleteSelected')}}');--}}





    </script>



@endsection


