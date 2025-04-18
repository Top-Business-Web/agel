@extends('admin/layouts/master')

@section('title')
{{ config()->get('app.name') }} | المكاتب
@endsection
@section('page_name')
    <!-- {{ $bladeName }} -->
@endsection
 <!-- {{--@section('page_name') {{ $title }} -->
  @endsection--}} -->

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

                                <option value="{{ $branch->id }}">{{ $branch->name }} ({{$offices->where('id', $branch->vendor_id)->first()?->parent_id==null?$offices->where('id', $branch->vendor_id)?->first()?->name:$offices->where('id', $offices->where('id', $branch->vendor_id)->first()->parent_id)->first()?->name}})</option>
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
{{--                        <button class="btn btn-secondary btn-icon text-white addBtn">--}}
{{--									<span>--}}
{{--										<i class="fe fe-plus"></i>--}}
{{--									</span> أضافه--}}
{{--                        </button>--}}
{{--                        <button class="btn btn-danger btn-icon text-white" id="bulk-delete">--}}
{{--                            <span><i class="fe fe-trash"></i></span> حذف المحدد--}}
{{--                        </button>--}}

{{--                        <button class="btn btn-secondary btn-icon text-white" id="bulk-update">--}}
{{--                            <span><i class="fe fe-trending-up"></i></span> تغير الحالة--}}
{{--                        </button>--}}
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
                                <th class="min-w-50px rounded-end">رقم الهويه</th>
                                <th class="min-w-50px rounded-end">رقم الهاتف</th>
                                <th class="min-w-50px rounded-end">الفرع</th>
{{--                                <th class="min-w-50px rounded-end">الموظف</th>--}}
                                <th class="min-w-50px rounded-end">المكتب</th>
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
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل أنت متأكد من أنك تريد حذف هذا العنصر <span id="title"
                                                                         class="text-danger"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            إلغاء
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
                        <p>هل أنت متأكد من أنك تريد حذف العناصر المحدده</p>
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

        <!-- delete selected  Modal -->


        <!-- update cols selected  Modal -->
        <div class="modal fade" id="updateConfirmModal" tabindex="-1" role="dialog"
             aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد التعديل</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من أنك تريد تعديل حالة العناصر المحدده</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">إلغاء
                        </button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn">تعديل</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'national_id', name: 'national_id'},
            {data: 'phone', name: 'phone'},
            {data: 'branch', name: 'branch'},
            {data: 'office', name: 'office'},
        ]

        // Initialize the DataTable
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{route($route.'.index')}}',
                data: function (d) {
                    d.branch_id = $('#branchFilter').val();
                    d.office_id = $('#officeFilter').val();
                }
            },
            columns: columns,
            order: [[0, 'desc']]
        });

        // Handle branch filter change
        $('#branchFilter').on('change', function() {
            // Reset office filter when branch changes
            $('#officeFilter').val('');
            dataTable.ajax.reload();
        });

        // Handle office filter change
        $('#officeFilter').on('change', function() {
            dataTable.ajax.reload();
        });

    </script>
@endsection


