<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">الصوره</label>
                    <input type="file" class="dropify" name="image" id="image"
                        data-default-file="{{ $obj->image }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name"
                           value="{{ $obj->name }}">
                </div>
            </div>



            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" name="email" id="email"
                           value="{{ $obj->email }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="has_parent" class="form-control-label">مندرج تحت مكتب</label>
                    <select class="form-control" name="has_parent" id="has_parent">
                        <option value="0">لا</option>
                        <option value="1">تعم</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="parent_id" class="form-control-label">المكتب</label>
                    <select placeholder="إختر المكتب" class="form-control" name="parent_id" id="parent_id" disabled>
                        @foreach ($vendors as $vendor)
                            <option value="{{$vendor->id }}">{{$vendor->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="city_id" class="form-control-label">المدينه</label>
                    <select placeholder="إختر المدينه" class="form-control" name="city_id" id="city_id">
                        @foreach ($cities as $city)
                            <option value="{{$city->id }}">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الهاتف</label>
                    <input type="number" class="form-control" name="phone" maxlength="11" id="name"
                        value="{{ $obj->phone }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الهويه</label>
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
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
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
<script>
    $(document).ready(function() {
        $('#has_parent').change(function() {
            if ($(this).val() == '0') {
                // Disable the parent_id select when 'no' is selected
                $('#parent_id').prop('disabled', true);
            } else {
                // Enable the parent_id select when 'yes' is selected
                $('#parent_id').prop('disabled', false);
            }
        });
    });
</script>
