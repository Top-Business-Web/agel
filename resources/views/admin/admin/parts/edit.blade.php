<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{route('admins.update',$admin->id)}}" >
    @csrf
        @method('PUT')
        <input type="hidden" value="{{$admin->id}}" name="id">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الإسم</label>
                    <input type="text" class="form-control" name="name" value="{{$admin->name}}" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="code" class="form-control-label">الكود</label>
                    <span class="form-control text-center">{{ $admin->code }}</span>
                    <input hidden type="hidden" class="form-control" name="code" value="{{ $admin->code }}" id="code">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">البريد الإلكتروني</label>
                    <input type="text" class="form-control" name="email" value="{{$admin->email}}" id="email">
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
            <div class="col-6">
                <div class="form-group">
                    <label for="role_id" class="form-control-label">صلاحيات النظام</label>
                    <select class="form-control" name="role_id" id="role_id">
                        <option value="">{{ trns('select_role') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ \App\Enums\RoleEnum::tryFrom($role->id)->label() }}" {{ $admin->hasRole($role->id) ? 'selected' : '' }}>
                                {{ \App\Enums\RoleEnum::tryFrom($role->id)->lang() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
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
