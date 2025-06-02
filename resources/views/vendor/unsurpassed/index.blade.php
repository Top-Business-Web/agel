@extends('vendor.layouts.master')
@section('title')
    {{ config()->get('app.name') }}
@endsection

<style>
    #loadingOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #f2f2f2;
        z-index: 9999;
        text-align: center;
        padding-top: 20%;
        font-size: 1.5rem;
        color: #fff;
        font-weight: bold;
    }

</style>
@section('content')
    <div id="loadingOverlay" style="
    display:none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgb(232,232,232);
    z-index: 9999;
    text-align: center;
    padding-top: 20%;
    font-size: 1.5rem;
    color: #fff;
    font-weight: bold;
">
        جاري تحميل البيانات من فضلك انتظر...
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-one-tab" data-bs-toggle="tab" href="#tab-one" role="tab"
                       aria-controls="tab-one" aria-selected="true">المتعثرين</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-two-tab" data-bs-toggle="tab" href="#tab-two" role="tab"
                       aria-controls="tab-two" aria-selected="false">متعثرين المكتب </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- التبويب الأول -->
                <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one-tab">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap w-100" id="dataTableWithoutButtons">
                                    <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>#</th>
                                        <th>اسم المستخدم</th>
                                        <th>رقم الهويه</th>
                                        <th>رقم الهاتف</th>
                                        <th>المكتب التابع له</th>
                                        <th>رقم هاتف المكتب</th>
                                        <th>حاله العميل</th>
                                        <th>ألإجراءات</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- التبويب الثاني -->
                <div class="tab-pane fade" id="tab-two" role="tabpanel" aria-labelledby="tab-two-tab">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div>
                                <button class="btn btn-secondary btn-icon text-white addBtn">أضافه</button>
                                <button class="btn btn-secondary btn-icon text-white addExcelFile">اكسل ملف</button>
                                <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                                    <span><i class="fe fe-trash"></i></span> حذف المحدد
                                </button>
                                <a href="{{ route('unsurpasseds.download.example') }}"
                                   class="btn btn-primary btn-icon text-white">
                                    <span><i class="fe fe-download"></i></span> تحميل مثال
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap w-100" id="dataTable">
                                    <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th>#</th>
                                        <th>اسم المستخدم</th>
                                        <th>رقم الهويه</th>
                                        <th>رقم الهاتف</th>
                                        <th>المكتب التابع له</th>
                                        <th>رقم هاتف المكتب</th>
                                        <th>حاله العميل</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متاكد من حذف هذا العنصر <span id="title" class="text-danger"></span>?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            أغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- add stock Modal -->
        <div class="modal fade" id="addStock" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">التفاصيل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body1">

                    </div>
                </div>
            </div>
        </div>
        <!-- add stock Modal -->

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
        <!-- delete selected  Modal -->
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
                        <p>هل أنت متأكد من أنك تريد حذق العناصر المحدده</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء
                        </button>
                        <button type="button" class="btn btn-danger" id="confirm-delete-btn">حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->


        <!-- Modals -->
        @include('vendor.layouts.myAjaxHelper')
    </div>
@endsection

@section('ajaxCalls')
    <script>
        var dataTableWithoutButtons = $('#dataTableWithoutButtons').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route($route . ".index") }}',
                type: 'GET',
            },
            columns: [
                {
                    data: 'checkbox', name: 'checkbox', orderable: false, searchable: false,
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
                    }
                },
                {data: 'id', name: 'id', orderable: false},
                {data: 'name', name: 'name', orderable: false},
                {data: 'national_id', name: 'national_id', orderable: false},
                {data: 'phone', name: 'phone', orderable: false},
                {data: 'office_name', name: 'office_name', orderable: false},
                {data: 'office_phone', name: 'office_phone', orderable: false},
                {data: 'client_status', name: 'client_status', orderable: false},
                {data: 'action', name: 'action', orderable: false}
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
            deferLoading: 0,
            searching: true,
        });

        // سلوك البحث مع ظهور الoverlay عند refresh الصفحة
        $('#dataTableWithoutButtons_filter input').off().on('input', function () {
            let searchValue = $(this).val().trim();

            if (searchValue === '') {
                // أظهر overlay
                $('#loadingOverlay').show();

                location.reload();
            } else {
                dataTableWithoutButtons.search(searchValue).draw();
            }
        });

        // الجدول الثاني بدون أزرار
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("myUnsurpassed") }}',
                type: 'GET',
            },
            columns: [
                {data: 'id', name: 'id', orderable: false, searchable: false},
                {data: 'name', name: 'name', orderable: false},
                {data: 'national_id', name: 'national_id', orderable: false},
                {data: 'phone', name: 'phone', orderable: false},
                {data: 'office_name', name: 'office_name', orderable: false},
                {data: 'office_phone', name: 'office_phone', orderable: false},
                {data: 'client_status', name: 'client_status', orderable: false},
            ],
            language: {
                search: "_INPUT_",
                orderable: false,
                searchable: false,
                searchPlaceholder: "ابحث...",
                emptyTable: "لا توجد بيانات متاحة في الجدول",
                info: "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
                infoEmpty: "عرض 0 إلى 0 من أصل 0 سجل",
                lengthMenu: "عرض _MENU_ سجل",
            },
        });

        // باقي السكريبتات كما هي
        deleteScript('{{ route($route . ".destroy", ":id") }}');
        deleteSelected('{{ route($route . ".deleteSelected") }}');
        showAddModal('{{ route($route . ".create") }}');
        addScript();
        showEditModal('{{ route($route . ".edit", ":id") }}');
        editScript();

        $('#loadData').click(function () {
            dataTable.ajax.reload();
        });

        $(document).on('click', '.addExcelFile', function () {
            let routeOfShow = '{{ route("unsurpasseds.add.excel") }}';
            $('#modal-excel-body').html(loader);
            $('#addExcelFile').modal('show');
            setTimeout(function () {
                $('#modal-excel-body').load(routeOfShow);
            }, 250);
        });
    </script>
@endsection
