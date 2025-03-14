<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">الصورة
                    </label>
                    <input type="file" class="dropify" name="image" id="image"
                           data-default-file="{{ getFile($obj->image)}}">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="region_id" class="form-control-label">اسم الحي</label>
                    <select class="form-control" name="region_id" id="region_id">
                        <option value="" selected disabled>اختر الحي</option>
                        @foreach ($regions as $region)
                            <option
                                value="{{$region->id }}" {{ $obj->region_id == $region->id ? 'selected' : '' }}>{{$region->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم
                    </label>
                    <input type="text" class="form-control" name="name" id="name"
                           value="{{ $obj->name }}">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني
                    </label>
                    <input type="email" class="form-control" name="email" id="email"
                           value="{{ $obj->email }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الجوال</label>
                    <input type="number" class="form-control" name="phone" maxlength="11" id="name"
                           value="{{ $obj->phone }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الهوية</label>
                    <input type="number" class="form-control" name="national_id" minlength="14" id="name"
                           value="{{ $obj->national_id }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">كلمة المرور</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password">
                </div>
            </div>


        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{أغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
