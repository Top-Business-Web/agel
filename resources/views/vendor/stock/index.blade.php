@extends('vendor.layouts.master')

@section('title')
@endsection
@section('page_name')
@endsection
@section('content')

<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"></h3>
                <div class="">
                    @can('create_stock')
                    <button class="btn btn-secondary btn-icon text-white addBtn">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> أضافه
                    </button>
                    @endcan

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

                                <th class="min-w-50px rounded-end">اسم الصنف</th>
                                <th class="min-w-50px rounded-end">الكميه</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="addStockBase" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">التفاصيل</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">

                </div>
            </div>
        </div>
    </div>


</div>
@include('vendor.layouts.myAjaxHelper')
@endsection
@section('ajaxCalls')
<script>
    var columns = [{
            data: 'checkbox',
            name: 'checkbox',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
            }
        },
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'name',
            name: 'name'
        },
        {
            data: 'stocks',
            name: 'stocks'
        },


    ]
    showData('{{route($route.'.index')}}', columns);
</script>
<script>
    $(document).on('click', '.addBtn', function() {
        let id = $(this).data('id');
        $('#modal-body').html(loader)
        $('#addStockBase').modal('show')
        setTimeout(function() {
            $('#modal-body').load('{{route($route.'.create')}}');
        }, 250);
    });
</script>
@endsection
