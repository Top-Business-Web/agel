<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('admins.store')}}">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="code" class="form-control-label">الكود</label>
                    <span class="form-control text-center">{{ $code }}</span>
                    <input hidden type="hidden" class="form-control" name="code" value="{{ $code }}" id="code">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="text" class="form-control" name="email" id="email">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">كلمة السر</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">تأكيد كلمة السر</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="role_id" class="form-control-label">صلاحيات النظام</label>
                    <select class="form-control" name="role_id" id="role_id">
                        <option value="">إختيار الصلاحيه</option>
                        @foreach($roles as $role)
                            <option value="{{ \App\Enums\RoleEnum::tryFrom($role->id)->label() }}">
                                {{ \App\Enums\RoleEnum::tryFrom($role->id)->lang() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ</button>
        </div>

    </form>
</div>



<script>
    $('.dropify').dropify();

    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
