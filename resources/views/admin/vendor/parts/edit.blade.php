<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image"
                        data-default-file="{{ $obj->image }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="module_id" class="form-control-label">{{ trns('Module') }}</label>
                    <select placeholder="{{ trns('-- select_module --') }}" class="form-control" name="module_id[]"
                        id="module_id" multiple>
                        @foreach ($moduleService as $module)
                            <option value="{{ $module->id }}" @if (in_array($module->id, $vendorModules)) selected @endif>
                                {{ $module->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }}</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ $obj->name }}">
                </div>
            </div>



            <div class="col-12">
                <div class="form-group">
                    <label for="email" class="form-control-label">{{ trns('email') }}</label>
                    <input type="email" class="form-control" name="email" id="email"
                        value="{{ $obj->email }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('phone') }}</label>
                    <input type="number" class="form-control" name="phone" maxlength="11" id="name"
                        value="{{ $obj->phone }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('national_id') }}</label>
                    <input type="number" class="form-control" name="national_id" minlength="14" id="name"
                        value="{{ $obj->national_id }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">{{ trns('password') }}</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">{{ trns('password_confirmation') }}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password">
                </div>
            </div>



        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>
