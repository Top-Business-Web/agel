@extends('vendor/layouts/master')

@section('title')
    {{ config()->get('app.name') }}
@endsection
@section('page_name')
    {{ $bladeName }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>


                    <div class="d-flex gap-2">
                        <select class="form-control select2" id="investorFilter">
                            <option value="">كل المستثمرين</option>
                            @foreach ($investors as $investor)
                                <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                            @endforeach
                        </select>

                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> أضافه
                        </button>
                    </div>

                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <h2 class=" text-center text-warning">
                            المبلغ الكلي {{ $investors->sum('balance') }}
                        </h2>
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">

                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-25px">المبلغ</th>

                                    <th class="min-w-25px">نوع العمليه</th>
                                    <th class="min-w-25px">التاريخ</th>
                                    <th class="min-w-25px">اسم المستثمر</th>
                                    <th class="min-w-25px">من قام بالعمليه</th>
                                    <th class="min-w-25px">الملاحظات</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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
        <!-- Create Or Edit Modal -->



    </div>
    @include('vendor/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        $('#investorFilter').select2({
            width: 'resolve'
        });

        let dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, "DESC"]
            ],
            ajax: {
                url: '{{ route($route . '.index') }}',
                data: function(d) {
                    d.investor_id = $('#investorFilter').val();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false, 
                    searchable: false
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'investor_id',
                    name: 'investor_id'
                },
                {
                    data: 'vendor_id',
                    name: 'vendor_id'
                },
                {
                    data: 'note',
                    name: 'note'
                },
            ]
        });

        // إعادة تحميل الجدول عند تغيير الفلتر
        $('#investorFilter').on('change', function() {
            dataTable.ajax.reload();
        });

        showAddModal('{{ route($route . '.create') }}');
        addScript();
    </script>
@endsection
