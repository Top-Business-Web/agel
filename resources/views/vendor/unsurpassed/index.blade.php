@extends('vendor.layouts.master')

@section('title')
    {{ config()->get('app.name') }}
@endsection
{{--@section('page_name')--}}
{{--    {{ $bladeName }}--}}
{{--@endsection--}}
@section('content')
{{--    @dd($obj)--}}

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
									<span>
										<i class="fe fe-plus"></i>
									</span> أضافه
                        </button>
                        <button class="btn btn-secondary btn-icon text-white addExcelFile">
									<span>
										<i class="fe fe-plus"></i>
									</span> excel رفع ملف
                        </button>
                        <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                            <span><i class="fe fe-trash"></i></span> حذف المحدد
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


        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="addExcelFile" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal4">التفاصيل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-add-excel-body" id="modal-add-excel-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Or Edit Modal -->

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


        <!-- update cols selected Modal -->
        <div class="modal fade" id="updateConfirmModal" tabindex="-1" role="dialog"
             aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد التغيير</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد أنك تريد تحديث العناصر المحددة؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn">تحديث</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- update selected Modal -->

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
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route($route.'.index')}}', columns);

        // Delete Using Ajax
        deleteScript('{{route($route.'.destroy',':id')}}');
        deleteSelected('{{route($route.'.deleteSelected')}}');

        updateColumnSelected('{{route($route.'.updateColumnSelected')}}');


        // Add Using Ajax
        showAddModal('{{route($route.'.create')}}');
        showAddExcelModal('{{route($route.'.add.excel')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route($route.'.edit',':id')}}');
        editScript();
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


        // Add this to your ajaxCalls section
        $(document).ready(function() {
            // Handle the Excel import form submission
            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            toastr.success(response.message);
                            $('#addExcelFile').modal('hide');

                            // Reload the DataTable
                            $('#dataTable').DataTable().ajax.reload(null, false);

                            // Reset the form
                            $('#addForm')[0].reset();
                            $('.dropify-clear').click(); // Clear the dropify file input
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                });
            });
        });

        // Your existing showAddExcelModal function should look something like this:


    </script>



@endsection


