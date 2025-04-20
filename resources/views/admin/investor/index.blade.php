@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | الاصناف
@endsection
@section('page_name')
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="form-group row">
                        <label for="branchFilter" class="col">اختر الفرع</label>
                        <select id="branchFilter" class="form-control col">
                            <option value="">الكل</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}
                                    ({{ $branch->vendor->parent_id != null ? $branch->vendor->parent->name : $branch->vendor->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="officeFilter" class="col">اختر المكتب</label>
                        <select id="officeFilter" class="form-control col">
                            <option value="">الكل</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
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

                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px rounded-end">الإسم</th>
                                    <th class="min-w-50px rounded-end">رقم الهويه</th>
                                    <th class="min-w-50px rounded-end">رقم الهاتف</th>
                                    <th class="min-w-50px rounded-end">الفرع</th>
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
        var columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'national_id',
                name: 'national_id'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'branch',
                name: 'branch'
            },
            {
                data: 'office',
                name: 'office'
            },
        ]

        // Initialize the DataTable
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route($route . '.index') }}',
                data: function(d) {
                    d.branch_id = $('#branchFilter').val();
                    d.office_id = $('#officeFilter').val();
                }
            },
            columns: columns,
            order: [
                [0, 'desc']
            ]
        });

        $('#branchFilter').on('change', function() {
            $('#officeFilter').val('');
            dataTable.ajax.reload();
        });

        $('#officeFilter').on('change', function() {
            dataTable.ajax.reload();
        });
    </script>
@endsection
