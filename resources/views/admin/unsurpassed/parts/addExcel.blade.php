<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeExcelRoute }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="excel_file" class="form-control-label">أرفق ملف المتعثرين</label>
                    <input type="file" class="dropify" name="excel_file" id="excel_file">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ
            </button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
