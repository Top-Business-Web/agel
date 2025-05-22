@extends('vendor.layouts.master')
@section('title')
    {{ config()->get('app.name') }}
@endsection
{{-- @section('page_name') --}}
{{--    {{ $bladeName }} --}}
{{-- @endsection --}}
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            أضافه
                        </button>
                        <button class="btn btn-secondary btn-icon text-white addExcelFile">
                            اكسل ملف
                        </button>
                        <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                            <span><i class="fe fe-trash"></i></span> حذف المحدد
                        </button>
                        <a href="{{ route('unsurpasseds.download.example') }}" class="btn btn-primary btn-icon text-white">
                            <span><i class="fe fe-download"></i></span> تحميل مثال
                        </a>
                        <button id="loadData" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> تحميل البيانات
                        </button>


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
                                    <th class="min-w-50px">حاله العميل</th>
                                    <th class="min-w-50px rounded-end">ألإجراءات</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل أنت متأكد أنك تريد حذف <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            إغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->


        <!-- addExcelFile Modal -->
        <div class="modal fade" id="addExcelFile" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal5">التفاصيل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-excel-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- addExcelFile Modal -->

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

        <!-- delete selected Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد أنك تريد حذف العناصر المحددة؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-danger" id="confirm-delete-btn">حذف</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- delete selected Modal -->




    </div>
    @include('vendor.layouts.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route($route . '.index') }}',
                type: 'GET',

            },
            columns: [
                {
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
                    data: 'national_id',
                    name: 'national_id'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'office_name',
                    name: 'office_name'
                },
                {
                    data: 'office_phone',
                    name: 'office_phone'
                },
                {
                    data: 'client_status',
                    name: 'client_status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],

            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "ابحث...",
                emptyTable: "لا توجد بيانات متاحة في الجدول",
                info: "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
                infoEmpty: "عرض 0 إلى 0 من أصل 0 سجل",
                lengthMenu: "عرض _MENU_ سجل",
            },
            // Prevent automatic data loading
            deferLoading: 0,
            initComplete: function(settings, json) {
                // Add any post-init code here
            }
        });

        // Delete Using Ajax
        deleteScript('{{ route($route . '.destroy', ':id') }}');
        deleteSelected('{{ route($route . '.deleteSelected') }}');

        // Add Using Ajax
        showAddModal('{{ route($route . '.create') }}');
        addScript();

        // Edit Using Ajax
        showEditModal('{{ route($route . '.edit', ':id') }}');
        editScript();

        // Load Data Button (optional)
        $('#loadData').click(function() {
            dataTable.ajax.reload();
        });

        $(document).on('click', '.addExcelFile', function() {
            let routeOfShow = '{{ route('unsurpasseds.add.excel') }}';
            console.log('addExcelFile');
            $('#modal-excel-body').html(loader);
            $('#addExcelFile').modal('show');
            setTimeout(function() {
                $('#modal-excel-body').load(routeOfShow);
            }, 250);
        });


    </script>
@endsection
