@extends('vendor/layouts/master')

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
                        @can('create_vendor')
                            <button class="btn btn-secondary btn-icon text-white addBtn">
                                <span>
                                    <i class="fe fe-plus"></i>
                                </span> إضافة
                            </button>
                        @endcan
                        @can('delete_vendor')
                            <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                                <span><i class="fe fe-trash"></i></span> حذف المحدد
                            </button>
                        @endcan
                        @can('update_vendor')
                            <button class="btn btn-secondary btn-icon text-white" id="bulk-update">
                                <span><i class="fe fe-trending-up"></i></span> تحديث المحدد
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
                                <th class="min-w-50px rounded-end">الاسم</th>
                                <th class="min-w-50px rounded-end">البريد الإلكتروني</th>
                                <th class="min-w-50px rounded-end">رقم الهاتف</th>
                                <th class="min-w-50px rounded-end">الرقم القومي</th>
                                <th class="min-w-50px rounded-end">الحالة</th>
                                <th class="min-w-50px rounded-end">الصورة</th>
                                <th class="min-w-50px rounded-end">الإجراءات</th>

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
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="إغلاق">
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
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->


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

        <!-- حذف العناصر المحددة - نافذة تأكيد -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"
             aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد أنك تريد حذف العناصر المحددة؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">إلغاء
                        </button>
                        <button type="button" class="btn btn-danger"
                                id="confirm-delete-btn">حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- نهاية نافذة تأكيد الحذف -->


        <!-- تحديث العناصر المحددة - نافذة تأكيد -->
        <div class="modal fade" id="updateConfirmModal" tabindex="-1" role="dialog"
             aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateConfirmModalLabel">تأكيد التغيير</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد أنك تريد تحديث العناصر المحددة؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">إلغاء
                        </button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn">تحديث</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- نهاية نافذة تأكيد التحديث -->

    </div>
    @include('vendor/layouts/myAjaxHelper')
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
            {data: 'id', name: 'id', visible: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'national_id', name: 'national_id'},
            {data: 'status', name: 'status'},
            {data: 'image', name: 'image'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route($route.'.index')}}', columns);

        // Delete Using Ajax
        deleteScript('{{route($route.'.destroy',':id')}}');
        deleteSelected('{{route($route.'.deleteSelected')}}');

        updateColumnSelected('{{route($route.'.updateColumnSelected')}}');


        // Add Using Ajax
        showAddModal('{{route($route.'.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route($route.'.edit',':id')}}');
        editScript();

        checkVendorKeyLimit('.addBtn', 'Vendor');

    </script>

    <script>
        // for status
        $(document).on('click', '.statusBtn', function () {
            let id = $(this).data('id');

            var val = $(this).is(':checked') ? 1 : 0;

            let ids = [id];


            $.ajax({
                type: 'POST',
                url: '{{ route($route . '.updateColumnSelected') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ids': ids,
                },
                success: function (data) {
                    if (data.status === 200) {
                        if (val !== 0) {
                            toastr.success('', "نشط");
                        } else {
                            toastr.warning('', "غير نشط ");
                        }
                    } else {
                        toastr.error('Error', "حدث خطأ ما");
                    }
                },
                error: function () {
                    toastr.error('Error', "حدث خطأ ما");
                }
            });
        });


    </script>
    {{--    <script>--}}
    {{--        $(document).on('submit', 'Form#addForm', function (e) {--}}
    {{--            e.preventDefault();--}}
    {{--            var formData = new FormData(this);--}}
    {{--            var url = $('#addForm').attr('action');--}}
    {{--            $.ajax({--}}
    {{--                url: url,--}}
    {{--                type: 'POST',--}}
    {{--                data: formData,--}}
    {{--                beforeSend: function () {--}}
    {{--                    $('#addButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +--}}
    {{--                        ' ></span> <span style="margin-left: 4px;">أنتظر قليلًا...</span>').attr('disabled', true);--}}
    {{--                },--}}
    {{--                success: function (data) {--}}
    {{--                    if (data.status == 200) {--}}
    {{--                        $('#dataTable').DataTable().ajax.reload();--}}
    {{--                        toastr.success('تمت العملية بنجاح');--}}
    {{--                    } else if(data.status == 405){--}}
    {{--                        toastr.error(data.mymessage);--}}
    {{--                    }--}}
    {{--                    else--}}
    {{--                        toastr.error('حدث خطأ ما');--}}
    {{--                    $('#addButton').html(`اضافه`).attr('disabled', false);--}}
    {{--                    // $('#editOrCreate').modal('hide')--}}
    {{--                },--}}
    {{--                error: function (data) {--}}
    {{--                    if (data.status === 500) {--}}
    {{--                        toastr.error('');--}}
    {{--                    } else if (data.status === 422) {--}}
    {{--                        var errors = $.parseJSON(data.responseText);--}}
    {{--                        $.each(errors, function (key, value) {--}}
    {{--                            if ($.isPlainObject(value)) {--}}
    {{--                                $.each(value, function (key, value) {--}}
    {{--                                    toastr.error(value, 'خطأ');--}}
    {{--                                });--}}
    {{--                            }--}}
    {{--                        });--}}
    {{--                    } else--}}
    {{--                        toastr.error('حدث خطأ ما');--}}
    {{--                    $('#addButton').html(`اضافة`).attr('disabled', false);--}}
    {{--                },//end error method--}}

    {{--                cache: false,--}}
    {{--                contentType: false,--}}
    {{--                processData: false--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}
@endsection


