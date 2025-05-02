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
                    <input type="number" class="form-control" name="national_id" id="national_id" minlength="10" maxlength="10" >
                </div>
            </div>


            <div class="col-6">
                <div class="form-group position-relative">
                    <label for="phone" class="form-control-label">رقم الهاتف
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="phone" id="phone" style=" text-align: left;">
                        <span class="input-group-text">966+</span>
                    </div>
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
                <div class="form-group position-relative">
                    <label for="office_phone" class="form-control-label">رقم المكتب</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="office_phone" id="office_phone" style=" text-align: left;" >
                        <span class="input-group-text">966+</span>
                    </div>
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
