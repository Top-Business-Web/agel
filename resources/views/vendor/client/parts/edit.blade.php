<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $obj->name }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <div class="input-group">
                        <span class="input-group-text">+966</span>
                        <input type="number" class="form-control" name="phone" maxlength="11" value="{{ substr($obj->phone,4) }}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="national_id" class="form-control-label">رقم الهويه</label>
                    <input type="number" class="form-control" name="national_id" id="national_id" value="{{ $obj->national_id }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">الفرع</label>
                    <select name="branch_id" class="form-control">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branch->id==$obj->branch_id ?"selected":"" }} >{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-success" id="updateButton">تعديل</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
