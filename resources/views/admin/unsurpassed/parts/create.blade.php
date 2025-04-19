<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم
                    </label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الهويه
                    </label>
                    <input type="text" class="form-control" name="national_id" id="national_id">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الهاتف
                    </label>
                    <input type="number" be class="form-control" name="phone" id="phone">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">اسم المكتب
                    </label>
                    <input type="text" class="form-control" name="office_name" id="office_name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم المكتب
                    </label>
                    <input type="text" class="form-control" name="office_phone" id="office_phone">
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
