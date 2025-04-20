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
                    <div class="form-group row">
                        <label for="officeFilter" class="col">اختر المكتب</label>
                        <select id="officeFilter" class="form-control col">
                            <option value="">الكل</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
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

            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'vendor', name: 'vendor'},

        ]



        // Hide the first column and remove checkboxes
        $('#dataTable').on('draw.dt', function () {
            var table = $('#dataTable').DataTable();
            table.column(0).visible(false);
            $('.select-all-checkbox, .delete-checkbox, .checkbox-selector').remove();
        });



        // Initialize the DataTable
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{route($route.'.index')}}',
                data: function (d) {
                    d.office_id = $('#officeFilter').val();
                }
            },
            columns: columns,
            order: [[0, 'desc']]
        });


        // Handle office filter change
        $('#officeFilter').on('change', function() {
            dataTable.ajax.reload();
        });


    </script>



@endsection


