<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">الصوره</label>
                    <input type="file" class="dropify" name="image" id="image">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="region_id" class="form-control-label">اسم الحي</label>
                    <select class="form-control" name="region_id" id="region_id">
                        <option value="" selected disabled>اختر الحي</option>
                        @foreach ($regions as $region)
                            <option value="{{$region->id }}">{{$region->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">اسم الفرع التابع له</label>
                    <select class="form-control" name="branch_ids[]" id="branch_id" multiple>
                        <option value=""  disabled>اختر الفرع</option>
                        @foreach ($branches as $branch)
                            <option value="{{$branch->id }}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <div class="input-group">
                        <span class="input-group-text">+966</span>
                        <input type="number" class="form-control" name="phone" maxlength="11">
                    </div>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">رقم الهويه</label>
                    <input type="number" class="form-control" name="national_id" minlength="14" id="name">
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

            <div class="col-lg-3 col-12 mb-2">
                <div class="name-rule">
                    <h5>صلاحيات المدير</h5>
                </div>
            </div>
            <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                    <label class="form-check-label" for="selectAllPermissions">اختيار الكل</label>
                </div>
                @foreach ($permissions->groupBy('parent_name') as $parent => $group)
            </div>
            <div class="col-lg-3 col-12 mb-2">
                <div class="name-rule">
                    <h5> {{trans('permissions.'.$parent)}} </h5>
                </div>
            </div>
            <div class="col-lg-9 col-12 d-flex flex-wrap justify-content-between mb-5">

                @foreach($group as $permission)
                    <div class="form-check">
                        <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" data-group="{{ $parent }}">
                        <label class="form-check-label" for="flexCheckDefault{{$loop->iteration}}">
                            {{getKey()[$loop->iteration-1]}}
                        </label>
                    </div>
                @endforeach

                @endforeach

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

    });

    document.getElementById('selectAllPermissions').addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
            groupCheckbox.checked = this.checked;
        });
    });

        document.getElementById('selectAllPermissions').addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        document.querySelectorAll('.parent-select-all').forEach(groupCheckbox => {
        groupCheckbox.checked = this.checked;
    });
    });

        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            let group = this.dataset.group;
            let secondPermission = document.querySelectorAll(`.permission-checkbox[data-group='${group}']`)[0];
            if (this.checked && secondPermission) {
                secondPermission.checked = true;
            }
        });
    });




</script>
